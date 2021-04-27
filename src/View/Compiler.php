<?php


namespace Max\View;


use Max\Tools\File;

class Compiler
{

    protected $debug;

    protected $viewPath;

    protected $compilePath;

    protected $cache;

    public function render(string $template, array $arguments)
    {
        $template     = $this->viewPath . $template;
        $data         = $this->getTemplateFile($template);
        $data         = $this->replace($data);
        $compiledFile = $this->compilePath . md5($template) . '.php';
        if (false === $this->cache || false === file_exists($compiledFile)) {
            File::mkdir($this->compilePath);
            file_put_contents($compiledFile, $data);
        }
        extract($arguments);
        include $compiledFile;
    }

    public function setCompilePath($path): Compiler
    {
        $this->compilePath = $path;
        return $this;
    }

    public function getTemplateFile($template)
    {
        if (!file_exists($template)) {
            throw new \Exception('Template ' . $template . ' does not exist');
        }
        return file_get_contents($template);
    }

    public function debug(bool $debug): Compiler
    {
        $this->debug = $debug;
        return $this;
    }

    public function setViewPath(string $path): Compiler
    {
        $this->viewPath = $path;
        return $this;
    }

    public function cache(bool $cache): Compiler
    {
        $this->cache = $cache;
        return $this;
    }

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
                '<?php include(\'\\1\'); ?>',
                '<?php echo \\1; ?>',
                '<?php foreach(\\1 as \\2): ?>',
                '<?php endforeach; ?>',
                '<?php echo \\2(\\1); ?>',
                '<?php if(\\1): ?>',
                '<?php endif; ?>',
                '<?php echo \\1(\\2) ?>',
            ],
            $template
        );
        return $compiled;
    }
}