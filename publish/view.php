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
