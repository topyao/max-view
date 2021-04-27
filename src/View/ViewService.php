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
            return invoke(['view', 'render'], [$template, $params]);
        }
    }
}

namespace Max\View {

    use Max\Contracts\Service;

    class ViewService implements Service
    {
        public function register()
        {
            app()->bind('view', Render::class);
        }

        public function boot()
        {
            \app('config')->load('view');
            app('env')->set(
                'view_path',
                app('config')->get('view.path', env('root_path') . 'views/')
            );
        }
    }
}
