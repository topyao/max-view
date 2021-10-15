<?php
declare(strict_types=1);

namespace Max\View;

use Max\Http\Response;

class Renderer
{

    /**
     * 容器实例
     *
     * @var App
     */
    protected $app;

    /**
     * Render constructor.
     *
     * @param App $app
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public static function __setter(\Max\App $app)
    {
        return new static($app->config->get('view'));
    }

    /**
     * @param string $key
     *
     * @return Engine
     */
    public function get($key = 'default')
    {
        if ('default' === $key) {
            $key = $this->config[$key];
        }
        $handler = $this->config[$key]['handler'];
        return new $handler($this->config[$key]['options']);
    }

    /**
     * 模板渲染方法
     *
     * @param       $template
     * 模板名
     * @param array $arguments
     * 参数
     *
     * @return mixed
     * @throws \Exception
     */
    public function render($template, $arguments = [])
    {
        ob_start();
        $view = $this->get()->render($template, $arguments) ?: ob_get_clean();
        return Response::html($view);
    }
}
