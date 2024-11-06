<?php 

namespace Core;

class App
{
    public static function start()
    {
        session_start();

        // Running config
        Config::load();

        // Running database
        DB::getInstance()->connect();

        // Running routes
        require_once 'routes/web.php';
        require_once 'routes/api.php';
        Route::run();
    }
}