<?php

namespace Max\View;

class Configure
{

    public static function install()
    {
        $root = getcwd();
        if (!is_dir($dir = $root . '/views')) {
            mkdir($dir, 0777);
        }
        if (!file_exists($root . '/config/view.php')) {
            if (copy(__DIR__ . '/../view.php', $root . '/config/view.php')) {
                echo "\033[32m Generate config file successfully: /config/view.php \033[0m \n";
            }
        }
    }

    public static function remove()
    {

    }

}