<?php
declare(strict_types=1);

namespace Max\View\Engines;

use Max\Config;
use Max\View\Engine;

class Smarty extends Engine
{

    /**
     * Smarty配置
     */
    public function __construct(array $options)
    {
        $this->handler                  = new \Smarty();
        $this->handler->debugging       = $options['debug'];
        $this->handler->caching         = $options['cache'];
        $this->handler->left_delimiter  = $options['left_delimiter'];
        $this->handler->right_delimiter = $options['right_delimiter'];
        $this->handler
            ->setTemplateDir(env('view_path'))
            ->setCompileDir(env('cache_path') . 'views/compile/')
            ->setCacheDir(env('cache_path') . 'views/');
    }

    /**
     * TODO
     * @param $template
     * @return mixed
     */
    protected function getTemplate($template)
    {
        return $template;
    }

    public function render(string $template, array $arguments = [])
    {
        foreach ($arguments as $key => $value) {
            $this->handler->assign($key, $value);
        }
        return $this->handler->display($this->getTemplate($template));
    }
}
