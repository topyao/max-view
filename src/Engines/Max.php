<?php

namespace Max\View\Engines;

use Max\View\Compiler;
use Max\View\Engine;

class Max extends Engine
{

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

    /**
     * 后缀
     *
     * @var string
     */
    protected string $suffix = '';

    /**
     * 初始化
     *
     * @throws \Exception
     */
    public function __construct($options)
    {
        parent::__construct($options);
        $this->handler = (new Compiler())
            ->setViewPath(env('view_path'))
            ->setCompilePath(env('cache_path') . 'views/compile/')
            ->cache($this->cache);
    }

    public function render(string $template, array $arguments = [])
    {
        try {
            return $this->handler->render($template . $this->suffix, $arguments);
        } catch (\Exception $e) {
            ob_clean();
            throw $e;
        }
    }

}
