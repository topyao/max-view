<?php

namespace Max\View\Engines;

use Max\View\Compiler;
use Max\View\Engine;

class Max extends Engine
{

    protected $suffix = '.html';

    /**
     * 初始化
     *
     * @throws \Exception
     */
    public function __construct($options)
    {
        foreach ($options as $key => $option) {
            $this->$key = $option;
        }
        $this->handler = (new Compiler())
            ->setViewPath(env('view_path'))
            ->setCompilePath(env('cache_path') . 'views/')
            ->cache($options['cache'] ?? false);
    }

    public function getTemplate(string $template)
    {
        return $template . $this->suffix;
    }

    public function render(string $template, array $arguments = [])
    {
        try {
            return $this->handler->render($this->getTemplate($template), $arguments);
        } catch (\Exception $e) {
            ob_clean();
            throw $e;
        }
    }

}
