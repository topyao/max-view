<?php

namespace Max\View;

use Max\Facades\Filesystem;

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
        $template     = $this->viewPath . $template;
        $data         = $this->replace($this->getTemplateFile($template));
        $compiledFile = $this->compilePath . md5($template) . '.php';
        if (false === $this->cache || false === Filesystem::exists($compiledFile)) {
            !Filesystem::isDirectory($this->compilePath) && Filesystem::makeDirectory($this->compilePath);
            Filesystem::put($compiledFile, $data);
        }
        extract($arguments);
        include $compiledFile;
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
        return Filesystem::put($template);
    }

    /**
     * debug开关
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

    /**
     * 模板字符串替换方法
     *
     * @param $template
     *
     * @return string|string[]|null
     */
    public function replace($template)
    {
        $compiled = preg_replace(
            [
                '/\{\{include [\'"]?(.*)[\'"]?\}\}/',
                '/\{\{(\$[\w]+)\}\}/',
                '/\{\{foreach (.+) as (.+)\}\}/',
                '/\{\{\/foreach\}\}/',
                '/\{\{(.*)\|(.*)\}\}/',
                '/\{\{if (.*)\}\}/',
                '/\{\{\/if\}\}/',
                '/\{\{(\w+)\(([\$\w]*)\)\}\}/'
            ],
            [
                '<?php include(\'\1\'); ?>',
                '<?php echo \1; ?>',
                '<?php foreach(\1 as \2): ?>',
                '<?php endforeach; ?>',
                '<?php echo \2(\1); ?>',
                '<?php if(\1): ?>',
                '<?php endif; ?>',
                '<?php echo \1(\2) ?>',
            ],
            $template
        );
        return $compiled;
    }
}