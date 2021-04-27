<?php

namespace Max\View\Engines;

use Max\View\Compiler;
use Max\View\Engine;

class Max extends Engine
{
    private $max;

    public function init()
    {
        $this->max = (new Compiler())
            ->setViewPath(env('view_path'))
            ->setCompilePath(env('cache_path'))
            ->cache($this->config['cache'] ?? false);
    }

    public function render($arguments = [])
    {
        return $this->max->render($this->template, $arguments);
    }
}