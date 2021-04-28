<?php
declare(strict_types=1);

namespace Max\View\Engines;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Max\View\Engine;

class Twig extends Engine
{

    /**
     * Twig实例
     * @var Environment
     */
    private $twig;

    public function init()
    {
        $loader = new FilesystemLoader(env('view_path'));
        $this->twig = new Environment($loader, [
            'debug' => $this->config['debug'],
            'cache' => $this->config['cache'] ? env('cache_path') . 'view' : false,
        ]);
    }

    /**
     * 渲染模板
     * @param array $arguments
     * @return mixed
     */
    public function render($arguments = [])
    {
        return $this->twig->render($this->template, $arguments);
    }
}
