<?php
declare(strict_types=1);

namespace Max\View;

use Psr\Http\Message\ResponseInterface;

class Renderer
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
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public static function __setter(\Max\App $app, ResponseInterface $response)
    {
        $renderer           = new static($app->config->get('view'));
        $renderer->response = $response;
        return $renderer;
    }

    /**
     * @param string $key
     * @return Engine
     */
    public function get($key = 'default')
    {
        $handler = $this->getHandler($key);
        return new $handler($this->getOptions($key));
    }

    protected function getOptions($key)
    {
        return $this->config[$key]['options'];
    }

    protected function getHandler($key)
    {
        return $this->config[$key]['handler'];
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
        $view = $this->get()->render($template, $arguments) ?: ob_get_clean();
        return $this->response->body($view);
    }
}
