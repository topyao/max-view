<?php

namespace {

    if (false === function_exists('view')) {
        /**
         * 视图赋值和渲染方法
         * @param string $template
         * 模板名，例如index@index对应index模块的index.html文件
         * @param array $params 需要渲染给模板的变量
         * @return mixed
         */
        function view(string $template, array $params = [])
        {
            return app('view')->render($template, $params);
        }
    }
}

namespace Max {

    use Max\Service;

    class ViewService extends Service
    {

        public function register()
        {
            $this->app->bind('view', \Max\View\Render::class);
        }

        public function boot()
        {
            $this->app->env->set(
                'view_path',
                $this->app->config->get('view.path', $this->app->env->get('root_path') . 'views/')
            );
        }

        public static function install()
        {
            $root = getcwd();
            if (!is_dir($dir = $root . '/views')) {
                mkdir($dir, 0777);
            }
            $file = '/config/view.php';
            if (!file_exists($root . $file)) {
                $config = <<<EOT
<?php

return [

    //模板引擎类型 twig,smarty
    'default' => 'max',

    //null使用默认view路径/views
    'path'    => null,

    'max' => [
        //模板调试
        'debug'  => false,
        //模板缓存
        'cache'  => false,
        //模板后缀
        'suffix' => 'html',
    ],

    'twig' => [
        //模板调试
        'debug'  => false,
        //模板缓存
        'cache'  => false,
        //模板后缀
        'suffix' => 'html',
    ],

    'smarty' => [
        //模板调试
        'debug'           => false,
        //模板缓存
        'cache'           => false,
        //模板后缀
        'suffix'          => 'html',
        //左右边界
        'left_delimiter'  => '{{',
        'right_delimiter' => '}}',
    ]

];

EOT;
                if (@file_put_contents($root . $file, $config)) {
                    echo "\033[32m Generate config file successfully: {$file} \033[0m \n";
                }
            }
        }

    }
}
