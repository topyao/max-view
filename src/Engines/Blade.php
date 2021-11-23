<?php

namespace Max\View\Engines;

use Max\View\Engines\Blade\Compiler;

class Blade extends AbstractEngine
{
    /**
     * 调试
     *
     * @var bool
     */
    protected bool $debug = false;

    /**
     * 缓存
     *
     * @var bool
     */
    protected bool $cache = false;

    /**
     * 后缀
     *
     * @var string
     */
    protected string $suffix = '.blade.php';

    /**
     * 视图根路径
     *
     * @var string
     */
    protected string $path = '';

    /**
     * 编译目录
     *
     * @var string
     */
    protected string $compileDir;

    /**
     * @var Compiler
     */
    protected Compiler $compiler;

    /**
     * Blade constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->compileDir = rtrim($options['compile_dir'], DIRECTORY_SEPARATOR) . '/';
        unset($options['compile_dir']);
        parent::__construct($options);
        $this->compiler = new Compiler($this);
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @return bool
     */
    public function isCacheable(): bool
    {
        return $this->cache;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getCompileDir(): string
    {
        return $this->compileDir;
    }

    public function render(string $template, array $arguments = [])
    {
        try {
            extract($arguments);
            include $this->compiler->compile($template);
        } catch (\Exception $e) {
            ob_clean();
            throw $e;
        }
    }

}