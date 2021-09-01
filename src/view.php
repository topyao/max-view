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