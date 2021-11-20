<?php
declare(strict_types=1);

namespace Max\View\Engines;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig extends AbstractEngine
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
        $loader        = new FilesystemLoader($options['path']);
        $this->handler = new Environment($loader, [
            'debug' => $options['debug'],
            'cache' => $options['cache'],
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
