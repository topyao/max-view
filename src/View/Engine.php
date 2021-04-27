<?php
declare(strict_types=1);

namespace Max\View;

use Max\Foundation\Config;

/**
 * Class Driver
 * @package Max\View
 */
abstract class Engine
{

    /**
     * 默认模板后缀
     */
    const SUFFIX = 'html';

    /**
     * View配置
     * @var array|mixed
     */
    protected $config = [];

    /**
     * 处理后的模板名
     * @var string
     */
    protected $template;

    /**
     * Driver constructor.
     * @param $template
     * 需要渲染的模板
     * @param Config $config
     */
    final public function __construct($template, Config $config)
    {
        $this->config   = $config->getDefault('view');
        $this->template = str_replace('/', DIRECTORY_SEPARATOR, $template) . '.' . ($this->config['suffix'] ?? self::SUFFIX);
        $this->init();
    }

    abstract public function init();

    /**
     * 驱动实现方法
     * @param array $arguments
     * 给模板传递的参数
     * @return mixed
     */
    abstract public function render($arguments = []);
}
