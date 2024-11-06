<?php 

namespace Core;

use Exception;

class Route
{
    private static $routes = [];

    public static function get($route, $callback)
    {
        self::$routes['GET'][$route] = [
            "class" => $callback[0],
            "method" => isset($callback[1]) ? $callback[1] : null
        ];;
    }

    public static function post($route, $callback)
    {
        self::$routes['POST'][$route] = [
            "class" => $callback[0],
            "method" => isset($callback[1]) ? $callback[1] : null
        ];
    }

    public static function delete($route, $callback)
    {
        self::$routes['DELETE'][$route] = [
            "class" => $callback[0],
            "method" => isset($callback[1]) ? $callback[1] : null
        ];
    }

    public static function patch($route, $callback)
    {
        self::$routes['PATCH'][$route] = [
            "class" => $callback[0],
            "method" => isset($callback[1]) ? $callback[1] : null
        ];
    }

    public static function run()
    {
        $q = $_GET["q"];
        $route = self::$routes[$_SERVER['REQUEST_METHOD']][$q] ?? null;
        if (isset($route)) {
            if($route["method"]!== null){
                $class = $route["class"];
                $method = $route["method"];

                if(method_exists($class, $method)){
                    $data = file_get_contents('php://input');

                    if($_SERVER['REQUEST_METHOD'] === "GET"){
                        echo call_user_func_array([new $class, $method], [$_GET]); // Запуск функции
                    } else {
                        echo call_user_func_array([new $class, $method], [json_decode($data, true)]); // Запуск функции
                    }

                } else {
                    echo exception_page("The '$method' method in the '$class' was not found"); // Вывод страницы с описанием ошибки
                }
            } else {
                echo $route["class"]();
            }
            die();
        }
        view("errors/404");
        die();
    }
}