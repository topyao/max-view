<?php
declare(strict_types=1);

namespace Max\View;

use Max\Foundation\App;

class Render
{

    /**
     * 容器实例
     * @var App
     */
    protected $app;

    /**
     * 驱动类名
     * @var string
     */
    public $engine = '';

    const NAMESPACE = '\\Max\\View\\Engines\\';

    /**
     * Render constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        if (!class_exists($this->engine = $app->config->get('view.default'))) {
            $this->engine = self::NAMESPACE . ucfirst($this->engine);
        }
    }

    /**
     * 模板渲染方法
     * @param $template
     * 模板名
     * @param array $arguments
     * 参数列表
     * @return mixed
     */
    public function render($template, $arguments = [])
    {
        return $this->app->invokeMethod([$this->engine, 'render'], [$arguments], true, [$template]);
    }
}
