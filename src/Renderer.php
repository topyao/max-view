<?php
declare(strict_types=1);

namespace Max\View;

use Max\Config\Repository;
use Max\View\Contracts\ViewEngineInterface;

class Renderer
{
    /**
     * @var ViewEngineInterface
     */
    protected ViewEngineInterface $viewEngine;

    /**
     * Renderer constructor.
     *
     * @param ViewEngineInterface $viewEngine
     */
    public function __construct(ViewEngineInterface $viewEngine)
    {
        $this->viewEngine = $viewEngine;
    }

    public static function __setter(Repository $repository)
    {
        $config  = $repository->get('view');
        $engine  = $config['engine'];
        $options = $config['options'];
        $engine  = new $engine($options);
        return new static($engine);
    }

    /**
     * @param string $template
     * @param array  $arguments
     *
     * @return false|string
     */
    public function render(string $template, array $arguments = [])
    {
        ob_start();
        echo (string)$this->viewEngine->render($template, $arguments);
        return ob_get_clean();
    }

    /**
     * @param $method
     * @param $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->viewEngine->{$method}($args);
    }
}
