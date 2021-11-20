<br>

<p align="center">
<img src="https://raw.githubusercontent.com/topyao/max/master/public/favicon.ico" width="120" alt="Max">
</p>

<p align="center">轻量 • 简单 • 快速</p>

<p align="center">
<img src="https://img.shields.io/badge/php-%3E%3D7.0.9-brightgreen">
<img src="https://img.shields.io/badge/license-apache%202-blue">
</p>

MaxPHP视图组件

# 安装

```
composer require max/view:dev-master
```

# 使用

## 注册服务提供者

在`/config/app.php` 的`provider`下注册服务提供者类`\Max\ViewService::class`

## 配置文件

安装完成后框架会自动将配置文件`view.php`移动到根包的`config`目录下，如果创建失败，可以手动创建。

文件内容如下：

```php
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

```

## 助手函数

安装完成后就可以使用`\Max\Foundation\Facades\View::render($template, $arguments);`等的方式来使用缓存扩展，或者使用助手函数`view()`

> 官网：https://www.chengyao.xyz

# 安装

```
composer require max/view:dev-master
```

# 使用

## 注册服务提供者

在`/config/provider.php` 的`http`中注册服务提供者类`\Max\ViewService::class`

## 配置文件

安装完成后框架会自动将配置文件`view.php`移动到根包的`config`目录下，如果创建失败，可以手动创建。

文件内容如下：

```php
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

//    'twig' => [
//        //模板调试
//        'debug'  => false,
//        //模板缓存
//        'cache'  => false,
//        //模板后缀
//        'suffix' => 'html',
//    ],
//
//    'smarty' => [
//        //模板调试
//        'debug'           => false,
//        //模板缓存
//        'cache'           => false,
//        //模板后缀
//        'suffix'          => 'html',
//        //左右边界
//        'left_delimiter'  => '{{',
//        'right_delimiter' => '}}',
//    ]

];

```

## 助手函数

安装完成后就可以使用`\Max\Foundation\Facades\View::render($template, $arguments);`等的方式来使用视图扩展，或者使用助手函数`view($template, $arguments)`

