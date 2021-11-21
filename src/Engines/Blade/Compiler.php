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
            '/@extends\([\'"](.*?)[\'"]\)/'                                                          => [$this, 'compileExtends'],
            '/@yield\([\'"]?(.*?)[\'"]?\)/'                                                          => [$this, 'compileYield'],
            '/@php([\s\S]*?)@endphp/'                                                                => [$this, 'compilePHP'],
            '/\{\{(?!--)(.*?)(?<!--)\}\}/'                                                           => [$this, 'compileEcho'],
            '/@include\([\'"](.*?)[\'"]\)/'                                                          => [$this, 'compileInclude'],
            '/(@if|@unless|@empty|@isset)\((.*)\)([\s\S]*?)(@endif|@endunless|@endempty|@endisset)/' => [$this, 'compileConditions'],
            '/@foreach\((.*?)\)([\s\S]*?)@endforeach/'                                               => [$this, 'compileForeach'],
            '/@for\((.*?)\)([\s\S]*?)@endfor/'                                                       => [$this, 'compileFor'],
            '/@switch\((.*?)\)([\s\S]*?)@endswitch/'                                                 => [$this, 'compileSwitch'],
            '/@section\([\'"](.*?)[\'"]\)([\s\S]*?)@endsection/'                                     => [$this, 'compileSection'],
        ], $this->readFile($this->getRealPath($file)));
    }

    /**
     * @param $matches
     *
     * @return string
     */
    protected function compileYield($matches): string
    {
        $value = explode('\',\'', str_replace(' ', '', $matches[1]), 2);

        return trim($this->sections[trim($value[0])] ?? (trim($value[1] ?? '')));
    }

    /**
     * @param $matches
     */
    protected function compileSection($matches)
    {
        $this->sections[$matches[1]] = $matches[2];
    }

    /**
     * @param $matches
     */
    protected function compileExtends($matches)
    {
        $this->parent = $matches[1];
    }

    /**
     * @param $matches
     *
     * @return array|string|string[]|null
     * @throws \Exception
     */
    protected function compileInclude($matches)
    {
        return $this->compileView($matches[1]);
    }

    /**
     * @param $matches
     *
     * @return string
     */
    protected function compileConditions($matches): string
    {
        [$statement, $condition, $content] = array_slice($matches, 1);
        switch ($statement = str_replace('@', '', $statement)) {
            case 'if':
                $content = preg_replace(
                    ['/@elseif\((.*)\)/', '/@else/'],
                    ['<?php elseif(\\1): ?>', '<?php else: ?>']
                    , $content
                );
                break;
            case 'unless':
                $condition = "!($condition)";
                break;
            default:
                $condition = sprintf('%s(%s)', $statement, $condition);
                break;
        }
        return sprintf('<?php if (%s): ?>%s<?php endif; ?>', $condition, $content);
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
    protected function compileForeach($matches): string
    {
        [$condition, $segment] = array_slice($matches, 1);
        return sprintf('<?php foreach (%s): ?>%s<?php endforeach; ?>', $condition, $segment);
    }

    /**
     * @param $matches
     *
     * @return string
     */
    protected function compileFor($matches): string
    {
        [$condition, $segment] = array_slice($matches, 1);
        return sprintf('<?php for (%s): ?>%s<?php endfor; ?>', $condition, $segment);
    }

    /**
     * @param $matches
     *
     * @return string
     */
    protected function compilePHP($matches): string
    {
        return sprintf("<?php%s?>", $matches[1]);
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
