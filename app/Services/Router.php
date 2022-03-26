<?php

namespace App\Services;

class Router
{

    /**
     * Список url страниц
     * @var mixed
     */
    private static array $list = [];

    /**
     * Метод регистрирует роут для страниц
     * @param mixed $uri
     * @param mixed $page_name
     * @return void
     */
    public static  function page($uri, $page_name)
    {
        self::$list[] = [
            "uri" => $uri,
            "page" => $page_name
        ];
    }

    /**
     * Метод для выполнения пост запроса
     * @param mixed $uri
     * @param mixed $class
     * @param mixed $method
     * @param mixed $formdata
     * @return void
     */
    public static function action($uri, $class, $method, $formdata = false)
    {
        self::$list[] = [
            'uri' => $uri,
            'class' => $class,
            'method' => $method,
            'post' => true,
            'formdata' => $formdata
        ];
    }

    public static function run()
    {
        // Получаем строку запроса
        $url = trim($_SERVER['REQUEST_URI'], '/');
        foreach (self::$list as $route) {
            // print_r($route);
            if ($route['uri'] === '/' . $url) {
                if (isset($route['post']) !== true || $_SERVER['REQUEST_METHOD'] !== 'POST') {
                    // если uri зарегестрированный в списке совпадает с тем что есть
                    require_once "views/pages/" . $route['page'] . ".php";
                } else {
                    // echo $_SERVER['REQUEST_METHOD'];
                    $action = new $route['class'];
                    $method = $route['method'];
                    
                    if ($route['formdata']) {
                        $action->$method($_POST);
                    } else {
                        $action->$method();
                    }
                    die();
                }
                die();
            }
        }

        self::error('404');
    }

    public static function redirect($url)
    {
        header('Location:' . $url);
    }

    // метод отображения несуществующей ссылки
    public static function error($error)
    {
        require_once "views/error/" . $error .  ".php";
    }
}
