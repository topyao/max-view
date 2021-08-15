<?php
declare(strict_types=1);

namespace Max\View;

use Max\App;

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

    /**
     * 驱动类基础命名空间
     */
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
     * 参数
     * @return mixed
     * @throws \Exception
     */
    public function render($template, $arguments = [])
    {
        ob_start();
        $view = $this->app->make($this->engine, [$template], true)->render($arguments);
        if (empty($view)) {
            $view = ob_get_clean();
        }
        return $this->app->response->body($view);
    }
}
