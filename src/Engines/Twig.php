<?php
declare(strict_types=1);

namespace Max\View\Engines;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Max\View\Engine;

class Twig extends Engine
{
    public function __construct($options)
    {
        $loader        = new FilesystemLoader(env('view_path'));
        $this->handler = new Environment($loader, [
            'debug' => $this->config['debug'],
            'cache' => $this->config['cache'] ? env('cache_path') . 'views/cache/' : false,
        ]);
    }

    /**
     * 渲染模板
     *
     * @param array $arguments
     *
     * @return mixed
     */
    public function render($arguments = [])
    {
        return $this->handler->render($this->template, $arguments);
    }
}
