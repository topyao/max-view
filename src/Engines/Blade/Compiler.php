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
            '/@(.*?)\([\'"](.*?)[\'"]\)/'                        => [$this, 'compileFunc'],
            '/@php/'                                             => [$this, 'compilePHP'],
            '/\{\{(?!--)([\s\S]*?)(?<!--)\}\}/'                  => [$this, 'compileEcho'],
            '/\{\{(?:--)[\s\S]*?--(?:\}\})/'                     => [$this, 'compileAnnotation'],
            '/@(if|unless|empty|isset)\((.*)?\)/'                => [$this, 'compileConditions'],
            '/@else(if\((.*)\))?/'                               => [$this, 'compileElse'],
            '/@for(each)?\((.*)?\)/'                             => [$this, 'compileLoop'],
            '/@switch\((.*?)\)([\s\S]*?)@endswitch/'             => [$this, 'compileSwitch'],
            '/@section\([\'"](.*?)[\'"]\)([\s\S]*?)@endsection/' => [$this, 'compileSection'],
            '/@end(?:(php|foreach|for|if|unless|empty|isset))/'  => [$this, 'compileEnd']
        ], $this->readFile($this->getRealPath($file)));
    }

    public function compileFunc(array $matches)
    {
        switch ($matches[1]) {
            case 'yield':
                $value = explode('\',\'', str_replace(' ', '', $matches[2]), 2);
                return $this->sections[$value[0]] ?? ($value[1] ?? '');
            case 'extends':
                $this->parent = $matches[2];
                break;
            case 'include':
                return $this->compileView($matches[2]);
            default:
                return $matches[0];
        }
    }

    public function compileEnd(array $matches)
    {
        switch ($endStr = $matches[1]) {
            case 'php':
                return '?>';
            case 'isset':
            case 'unless':
            case 'empty':
                return sprintf('<?php endif; ?>', $endStr);
            default :
                return sprintf('<?php end%s; ?>', $endStr);
        }
    }

    /**
     * @param array $matches
     *
     * @return string
     */
    public function compileAnnotation(array $matches): string
    {
        return '';
    }

    /**
     * @param $matches
     */
    protected function compileSection($matches)
    {
        $this->sections[$matches[1]] = $matches[2];
    }

    /**
     * @param array $matches
     *
     * @return string
     */
    protected function compileElse(array $matches)
    {
        return sprintf('<?php else%s: ?>', $matches[1] ?? '');
    }

    /**
     * @param $matches
     *
     * @return string
     */
    protected function compileConditions($matches): string
    {
        [$statement, $condition] = array_slice($matches, 1);
        switch ($statement) {
            case 'if':
                break;
            case 'unless':
                $condition = "!($condition)";
                break;
            default:
                $condition = sprintf('%s(%s)', $statement, $condition);
                break;
        }
        return sprintf('<?php if (%s): ?>', $condition);
    }

    /**
     * @param $matches
     *
     * @return string
     */
    protected function compileEcho($matches): string
    {
        return sprintf('<?php echo %s; ?>', $matches[1]);
    }

    /**
     * @param $matches
     *
     * @return string
     */
    protected function compileLoop($matches): string
    {
        [$each, $condition] = array_slice($matches, 1);

        return sprintf('<?php for%s (%s): ?>', $each, $condition);
    }

    /**
     * @param $matches
     *
     * @return string
     */
    protected function compilePHP($matches): string
    {
        return "<?php";
    }

    /**
     * @param array $matches
     *
     * @return string
     */
    protected function compileSwitch(array $matches): string
    {
        [$condition, $segment] = array_slice($matches, 1);
        $segment = preg_replace(
            ['/@case\((.*)\)/', '/@default/',],
            ["<?php case \\1: ?>", '<?php default: ?>',],
            $segment
        );

        return sprintf('<?php switch(%s): ?>%s<?php endswitch; ?>', $condition, trim($segment));
    }

}
