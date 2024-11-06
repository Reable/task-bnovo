<?php 

namespace Core;

use Dotenv\Dotenv;

class Config
{

    public static function load()
    {
        Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

        self::load_globals_functions();
    }

    public static function load_globals_functions()
    {
        $files = glob("config/Globals" . '/*.php');
        foreach ($files as $file) {
            require_once($file);
        }
    }
}