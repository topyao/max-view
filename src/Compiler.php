<?php

namespace Max\View;

use Max\Foundation\Facades\Filesystem;
use Max\View\Compiler\Rules;
use Max\View\Compiler\Substitute;

class Compiler
{
    /**
     * 调试模式
     * bool @var
     */
    protected $debug;

    /**
     * 缓存路径
     * string @var
     */
    protected $viewPath;

    /**
     * 编译目录
     *
     * @var
     */
    protected $compilePath;

    /**
     * 缓存标识
     *
     * @var
     */
    protected $cache;

    /**
     * 模板渲染
     *
     * @param string $template
     * @param array  $arguments
     *
     * @throws \Exception
     */
    public function render(string $template, array $arguments)
    {
        extract($arguments);
        include $this->getTemplateDir($template);
    }

    protected function getTemplateDir($template = '')
    {
        $template     = $this->viewPath . $template;
        $data         = Substitute::compile($this->getTemplateFile($template));
        $compiledFile = $this->compilePath . md5($template) . '.php';
        if (false === $this->cache || false === Filesystem::exists($compiledFile)) {
            !Filesystem::isDirectory($this->compilePath) && Filesystem::makeDirectory($this->compilePath, 0755, true);
            Filesystem::put($compiledFile, $data);
        }
        return $compiledFile;
    }

    /**
     * 设置编译文件目录
     *
     * @param $path
     *
     * @return $this
     */
    public function setCompilePath($path): Compiler
    {
        $this->compilePath = $path;
        return $this;
    }

    /**
     * 获取模板路径
     *
     * @param $template
     *
     * @return false|string
     * @throws \Exception
     */
    public function getTemplateFile($template)
    {
        if (!Filesystem::exists($template)) {
            throw new \Exception('Template ' . $template . ' does not exist');
        }
        return Filesystem::get($template);
    }

    /**
     * debug
     *
     * @param bool $debug
     *
     * @return $this
     */
    public function debug(bool $debug): Compiler
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * 设置视图目录
     *
     * @param string $path
     *
     * @return $this
     */
    public function setViewPath(string $path): Compiler
    {
        $this->viewPath = $path;
        return $this;
    }

    /**
     * 是否缓存
     *
     * @param bool $cache
     *
     * @return $this
     */
    public function cache(bool $cache): Compiler
    {
        $this->cache = $cache;
        return $this;
    }
}
