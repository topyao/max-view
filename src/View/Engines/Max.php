<?php

namespace Max\View\Engines;

use Max\View\Compiler;
use Max\View\Engine;

class Max extends Engine
{
    /**
     * Max Compiler示例
     * @var
     */
    private $max;

    /**
     * 初始化
     * @throws \Exception
     */
    public function init()
    {
        $this->max = (new Compiler())
            ->setViewPath(env('view_path'))
            ->setCompilePath(env('cache_path') . 'views/')
            ->cache($this->config['cache'] ?? false);
    }

    /**
     * 模板渲染
     * @param array $arguments
     * @return mixed
     */
    public function render($arguments = [])
    {
        return $this->max->render($this->template, $arguments);
    }
}