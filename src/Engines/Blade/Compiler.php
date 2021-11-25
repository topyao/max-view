<?php

namespace Max\View\Engines\Blade;

use Max\View\Engines\Blade;
use Max\View\Exceptions\ViewNotExistException;

class Compiler
{
    /**
     * @var array
     */
    protected array $sections = [];

    /**
     * @var string|null
     */
    protected ?string $parent;

    /**
     * @var Blade
     */
    protected Blade $blade;

    /**
     * Compiler constructor.
     *
     * @param Blade $blade
     */
    public function __construct(Blade $blade)
    {
        $this->blade = $blade;
    }

    /**
     * 读模板
     *
     * @param $template
     *
     * @return mixed
     * @throws \Exception
     */
    protected function readFile($template)
    {
        if (file_exists($template)) {
            return file_get_contents($template);
        }
        throw new ViewNotExistException('View ' . $template . ' does not exist');
    }

    /**
     * @param $template
     *
     * @return string
     */
    protected function getRealPath($template): string
    {
        return sprintf('%s/%s%s',
            rtrim($this->blade->getPath(), DIRECTORY_SEPARATOR),
            $template,
            $this->blade->getSuffix()
        );
    }

    /**
     * 编译
     *
     * @param $template
     *
     * @return string
     */
    public function compile($template): string
    {
        $compileDir   = $this->blade->getCompileDir();
        $compiledFile = $compileDir . md5($template) . '.php';

        if (false === $this->blade->isCacheable() || false === file_exists($compiledFile)) {
            !is_dir($compileDir) && mkdir($compileDir, 0755, true);
            $stream = $this->compileView($template);
            while (isset($this->parent)) {
                $parent       = $this->parent;
                $this->parent = null;
                $stream       = $this->compileView($parent);
            }
            file_put_contents($compiledFile, $stream);
        }

        return $compiledFile;
    }

    /**
     * 编译文件
     *
     * @param string $file
     *
     * @return array|string|string[]|null
     * @throws \Exception
     */
    protected function compileView(string $file)
    {
        return preg_replace_callback_array([
            '/@(.*?)\((.*)?\)/'                                                        => [$this, 'compileFunc'],
            '/\{!!([\s\S]*?)!!\}/'                                                     => [$this, 'compileRaw'],
            '/\{\{((--)?)([\s\S]*?)\\1\}\}/'                                           => [$this, 'compileEchos'],
            '/@(section|switch)\((.*?)\)([\s\S]*?)@end\\1/'                            => [$this, 'compileParcel'],
            '/@(php|else|endphp|endforeach|endfor|endif|endunless|endempty|endisset)/' => [$this, 'compileDirective']
        ], $this->readFile($this->getRealPath($file)));
    }

    /**
     * @param array $matches
     *
     * @return string
     */
    protected function compileRaw(array $matches)
    {
        return sprintf('<?php echo %s; ?>', $matches[1]);
    }

    /**
     * 编译输出内容
     *
     * @param array $matches
     *
     * @return string|void
     */
    protected function compileEchos(array $matches)
    {
        if ('' === $matches[1]) {
            return sprintf('<?php echo htmlspecialchars(%s, ENT_QUOTES); ?>', $matches[3]);
        }
    }

    /**
     * 编译包裹内容
     *
     * @param array $matches
     *
     * @return string|void
     */
    protected function compileParcel(array $matches)
    {
        [$directive, $condition, $segment] = array_slice($matches, 1);
        switch ($directive) {
            case 'section':
                $this->sections[$this->trim($condition)] = $segment;
                break;
            case 'switch':
                $segment = preg_replace(
                    ['/@case\((.*)\)/', '/@default/',],
                    ["<?php case \\1: ?>", '<?php default: ?>',],
                    $segment
                );
                return sprintf('<?php switch(%s): ?>%s<?php endswitch; ?>', $condition, trim($segment));
        }
    }

    /**
     * 编译指令
     *
     * @param array $matches
     *
     * @return string
     */
    protected function compileDirective(array $matches)
    {
        switch ($directive = $matches[1]) {
            case 'php':
                return '<?php ';
            case 'endphp':
                return '?>';
            case 'else':
                return '<?php else: ?>';
            case 'endisset':
            case 'endunless':
            case 'endempty':
                return sprintf('<?php endif; ?>', $directive);
            default :
                return sprintf('<?php %s; ?>', $directive);
        }
    }

    /**
     * 编译函数
     *
     * @param array $matches
     *
     * @return array|mixed|string|string[]|void|null
     * @throws \Exception
     */
    protected function compileFunc(array $matches)
    {
        [$func, $arguments] = [$matches[1], $this->trim($matches[2])];
        switch ($func) {
            case 'yield':
                $value = array_map(function($v) {
                    return $this->trim($v);
                }, explode(',', $arguments, 2));
                return $this->sections[$value[0]] ?? ($value[1] ?? '');
            case 'extends':
                $this->parent = $arguments;
                break;
            case 'include':
                return $this->compileView($arguments);
            case 'if':
                return sprintf('<?php if (%s): ?>', $arguments);
            case 'unless':
                return sprintf('<?php if (!(%s)): ?>', $arguments);
            case 'empty':
            case 'isset':
                return sprintf('<?php if (%s(%s)): ?>', $func, $arguments);
            case 'for':
            case 'foreach':
                return sprintf('<?php %s(%s): ?>', $func, $arguments);
            case 'elseif':
                return sprintf('<?php elseif(%s): ?>', $arguments);
            default:
                return $matches[0];
        }
    }

    protected function trim(string $value)
    {
        return trim($value, '\'" ');
    }
}
