<?php
declare(strict_types=1);

namespace Max\View\Engines;

use Max\View\Engine;

class Smarty extends Engine
{

    /**
     * Smarty实例
     * @var \Smarty
     */
    private $smarty;

    /**
     * Smarty配置
     */
    public function init()
    {
        $this->smarty = new \Smarty();
        $this->smarty->debugging = $this->config['debug'];
        $this->smarty->caching = $this->config['cache'];
        $this->smarty->left_delimiter = $this->config['left_delimiter'];
        $this->smarty->right_delimiter = $this->config['right_delimiter'];
        $this->smarty
            ->setTemplateDir(env('view_path'))
            ->setCompileDir(env('cache_path') . 'views/compile/')
            ->setCacheDir(env('cache_path') . 'views/');
    }

    /**
     * @param array $arguments
     * @return mixed|void
     * @throws \SmartyException
     */
    public function render($arguments = [])
    {
        foreach ($arguments as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        return $this->smarty->display($this->template);
    }
}
