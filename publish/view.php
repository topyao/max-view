<?php

return [
    'engine' => \Max\View\Engines\Blade::class,
    'options' => [
        // 模板目录
        'path' => __DIR__ . '/../views/',
        // 编译和缓存目录
        'compile_dir' => __DIR__ . '/../storage/cache/views/compile',
        // 模板缓存
        'cache' => false,
        // 模板后缀
        'suffix' => '.blade.php',
    ],
];
