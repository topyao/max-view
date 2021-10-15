<?php
declare(strict_types=1);

namespace Max\View\Engines;

use Max\View\Engine;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig extends Engine
{
    /**
     * 后缀
     *
     * @var string
     */
    protected string $suffix = '';

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

    public function __construct(array $options)
    {
        parent::__construct($options);
        $loader        = new FilesystemLoader(env('view_path'));
        $this->handler = new Environment($loader, [
            'debug' => $this->debug,
            'cache' => $this->cache ? env('cache_path') . 'views/cache/' : false,
        ]);
    }

    /**
     * 渲染模板
     *
     * @param array $arguments
     *
     * @return mixed
     */
    public function render(string $template, array $arguments = [])
    {
        return $this->handler->render($template . $this->suffix, $arguments);
    }
}
