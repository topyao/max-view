<?php
declare(strict_types=1);

namespace Max\View\Engines;

use Max\View\Engine;

class Smarty extends Engine
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

    /**
     * 左边界
     *
     * @var string
     */
    protected string $leftDelimiter;

    /**
     * 右边界
     *
     * @var string
     */
    protected string $rightDelimiter;

    /**
     * Smarty配置
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
        $this->handler                  = new \Smarty();
        $this->handler->debugging       = $this->debug;
        $this->handler->caching         = $this->cache;
        $this->handler->left_delimiter  = $this->leftDelimiter;
        $this->handler->right_delimiter = $this->rightDelimiter;
        $this->handler->setTemplateDir(env('view_path'))
            ->setCompileDir(env('cache_path') . 'views/compile/')
            ->setCacheDir(env('cache_path') . 'views/cache');
    }

    public function render(string $template, array $arguments = [])
    {
        foreach ($arguments as $key => $value) {
            $this->handler->assign($key, $value);
        }
        return $this->handler->display($template . $this->suffix);
    }
}
