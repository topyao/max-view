<?php
declare(strict_types=1);

namespace Max\View;

use Max\Config;

/**
 * Class Driver
 * @package Max\View
 */
abstract class Engine
{

    protected $handler;

    /**
     * Driver constructor.
     * @param $template
     * 需要渲染的模板
     * @param Config $config
     */
    abstract public function __construct(array $options);

    /**
     * 驱动实现方法
     * @param array $arguments
     * 给模板传递的参数
     * @return mixed
     */
    abstract public function render(string $template, array $arguments = []);
}
