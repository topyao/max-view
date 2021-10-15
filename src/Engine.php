<?php
declare(strict_types=1);

namespace Max\View;

use Max\Config;

/**
 * Class Driver
 *
 * @package Max\View
 */
abstract class Engine
{
    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        foreach ($options as $key => $option) {
            $this->$key = $option;
        }
    }

    /**
     * 驱动实现方法
     *
     * @param array $arguments
     * 给模板传递的参数
     *
     * @return mixed
     */
    abstract public function render(string $template, array $arguments = []);
}
