<?php

namespace Max\View {

    use Max\ServiceProvider;

    class ViewServiceProvider extends ServiceProvider
    {

        public function register()
        {
            $this->app->alias('view', Renderer::class);
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

namespace {

    /**
     * 视图赋值和渲染方法
     *
     * @param string $template
     *                       模板名，例如index@index对应index模块的index.html文件
     * @param array  $params 需要渲染给模板的变量
     *
     * @return mixed
     */
    function view(string $template, array $params = [])
    {
        return make('view')->render($template, $params);
    }

}

