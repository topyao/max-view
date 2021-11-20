<br>

<p align="center">
<img src="https://raw.githubusercontent.com/topyao/max/master/public/favicon.ico" width="120" alt="Max">
</p>

<p align="center">轻量 • 简单 • 快速</p>

<p align="center">
<img src="https://img.shields.io/badge/php-%3E%3D7.0.9-brightgreen">
<img src="https://img.shields.io/badge/license-apache%202-blue">
</p>

MaxPHP视图组件，支持Blade，Smarty，Twig
# 安装

```
composer require max/view:dev-master
```

# 使用

> Blade 可能会有未知的Bug，使用时需要注意，Blade引擎支持的语法如下

- {{}}
- {{--  --}}
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

## 配置文件

安装完成后框架会自动将配置文件`view.php`移动到根包的`config`目录下，如果创建失败，可以手动创建。

文件内容如下：

```php
<?php

return [
    'engine' => \Max\View\Engines\Blade::class,
    'options' => [
        // 模板目录
        'path' => realpath(__DIR__ . '/../views/'),
        // 编译和缓存目录
        'compile_dir' => realpath(__DIR__ . '/../storage/cache/views/compiled'),
        // 模板调试
        'debug' => false,
        // 模板缓存
        'cache' => false,
        // 模板后缀
        'suffix' => '.blade.php',
    ],
//    'engine' => \Max\View\Engines\Twig::class,
//    'options' => [
//        'path' => realpath(__DIR__ . '/../views/'),
//        //模板调试
//        'debug' => false,
//        //模板缓存或者缓存路径
//        'cache' => false,
//        //模板后缀
//        'suffix' => '.html',
//    ],
//
//    'engine' => \Max\View\Engines\Smarty::class,
//    'options' => [
//        // 模板目录
//        'path' => realpath(__DIR__ . '/../views/'),
//        'compile_dir' => realpath(__DIR__ . '/../storage/cache/views/compiled'),
//        'cache_dir' => realpath(__DIR__ . '/../storage/cache/views/compiled'),
//        //模板调试
//        'debug' => false,
//        //模板缓存
//        'cache' => false,
//        //模板后缀
//        'suffix' => '.html',
//        //左右边界
//        'left_delimiter' => '{{',
//        'right_delimiter' => '}}',
//    ],
];
```

## 助手函数

安装完成后就可以使用`\Max\Foundation\Facades\View::render($template, array $arguments = []);`等的方式来使用缓存扩展，或者使用助手函数`view()`

> 官网：https://www.chengyao.xyz