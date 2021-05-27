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

    use Max\Contracts\Service;

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
    }
}
