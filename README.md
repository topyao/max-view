<br>

<p align="center">
<img src="https://raw.githubusercontent.com/topyao/max/master/public/favicon.ico" width="120" alt="Max">
</p>

<p align="center">轻量 • 简单 • 快速</p>

<p align="center">
<img src="https://img.shields.io/badge/php-%3E%3D7.4-brightgreen">
<img src="https://img.shields.io/badge/license-apache%202-blue">
</p>

MaxPHP视图组件，支持Blade，Smarty，Twig。 可以独立使用!

# 安装

```
composer require max/view:dev-master
```

# 使用

> Blade 可能会有未知的Bug，使用时需要注意，Blade引擎支持的语法如下

- {{}}
- {{-- --}}
- @extends
- @yield
- @php
- @include
- @if
- @unless
- @empty
- @isset
- @foreach
- @for
- @switch
- @section

> 如果使用`extends` + `yield` + `section`, 务必保证子模板中除了`extends` 之外的所有代码均被`section` 包裹

## 配置文件

安装完成后框架会自动将配置文件`view.php`移动到根包的`config`目录下，如果创建失败，可以手动创建。

文件内容如下：

```php
<?php

return [
    'engine' => \Max\View\Engines\Blade::class,
    'options' => [
        // 模板目录
        'path' => __DIR__ . '/../views/',
        // 编译和缓存目录
        'compile_dir' => __DIR__ . '/../storage/cache/views/compiled',
        // 模板调试
        'debug' => false,
        // 模板缓存
        'cache' => false,
        // 模板后缀
        'suffix' => '.blade.php',
    ],
];

```

如果你使用`Smarty` 或者`blade`, 配置文件可以按照下面的例子修改

```php
 <?php

return [
    // Twig
    'engine' => \Max\View\Engines\Twig::class,
    'options' => [
        'path' => __DIR__ . '/../views/',
        //模板调试
        'debug' => false,
        //模板缓存或者缓存路径
        'cache' => false,
        //模板后缀
        'suffix' => '.html',
    ],

    // Smarty
    'engine' => \Max\View\Engines\Smarty::class,
    'options' => [
        // 模板目录
        'path' => __DIR__ . '/../views/',
        'compile_dir' => __DIR__ . '/../storage/cache/views/compiled',
        'cache_dir' => __DIR__ . '/../storage/cache/views/compiled',
        //模板调试
        'debug' => false,
        //模板缓存
        'cache' => false,
        //模板后缀
        'suffix' => '.html',
        //左右边界
        'left_delimiter' => '{{',
        'right_delimiter' => '}}',
    ],
];   
```

## 使用

> 以下以`Blade`为例

```php
// 实例化Blade
$blade = new Blade(config('view.options'));
// 实例化渲染器，传入Blade
$renderer = new Renderer($blade);
// 渲染模板
return $renderer->render('index', ['test' => ['123']]);
```

### 自定义引擎

自定义引擎必须实现`ViewEngineInterface`接口, 将新的引擎实例传递给渲染器即可

### 助手函数
> 如果你使用MaxPHP, 则可以使用助手函数和门面

```php
\Max\Foundation\Facades\View::render($template, array $arguments = []);

view($template, array $arguments = []);
```

> 官网：https://www.chengyao.xyz
