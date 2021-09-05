<?php

return [
    'default' => 'max',
    'max'     => [
        'handler' => \Max\View\Engines\Max::class,
        'options' => [
            //模板调试
            'debug'  => false,
            //模板缓存
            'cache'  => false,
            //模板后缀
            'suffix' => 'html',
        ],
    ],
    'twig'    => [
        'handler' => \Max\View\Engines\Twig::class,
        'options' => [
            //模板调试
            'debug'  => false,
            //模板缓存
            'cache'  => false,
            //模板后缀
            'suffix' => 'html',
        ],
    ],
    'smarty'  => [
        'handler' => \Max\View\Engines\Smarty::class,
        'options' => [
            //模板调试
            'debug'           => false,
            //模板缓存
            'cache'           => false,
            //模板后缀
            'suffix'          => 'html',
            //左右边界
            'left_delimiter'  => '{{',
            'right_delimiter' => '}}',
        ],
    ],

    //null使用默认view路径/views
    'path'    => null,


];