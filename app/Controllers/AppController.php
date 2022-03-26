<?php

namespace App\Controllers;

use App\Models\Session;
use App\Models\Users;

/**
 * родительский класс для контроллеров
 * содержит общие методы
 */
class AppController
{

    public static function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") {
            return true;
        } else {
            return false;
        }
    }

    // /**
    //  * проверка есть ли сессия
    //  * @return bool
    //  */
    // public static function existSession()
    // {
    //     if (isset($_SESSION['auth']) && $_SESSION['auth']) {
    //         // сравниваем сессионный ключ и ключ в БД
    //         $users = new Users();
    //         if ($users->equalitySessionKey($_SESSION['login'], $_SESSION['sessionKey'])) {
    //             return true;
    //         }
    //     }
    //     return false;
    // }

    /**
     * проверяем сессию, если есть то не гость,
     * если сессии нет, то смотрим куки
     * если куки есть, создаём сессию,
     * если нет, значит он гость
     * @return bool
     */
    // public static function isGuest()
    // {
    //     // если сеесия есть, то не гость
    //     if (self::existSession()) return false;
    //     //  если куки есть и он не пустой, то не гость
    //     if (isset($_COOKIE['login']) && $_COOKIE['login'] != '') {
    //         $users = new Users();
    //         // если куки-ключ совпадает с ключем в БД
    //         if ($users->equalityCookieKey($_COOKIE['login'], $_COOKIE['cookieKey'])) {
    //             // делаем новую сессию
    //             $numberUser = (int)$users->searchObjectNumberByLogin($_COOKIE['login']);

    //             Session::create($users, $numberUser);
    //             return false;
    //         }
    //     }
    //     return true;
    // }

    /**
     * выводим json для ajax запросов
     * @param  mixed $param
     */
    public static function responseJson($param = [])
    {
        header('Content-type: application/json');
        echo json_encode($param);
        exit;
    }

    /**
     * Перенаправление страниц
     * @param mixed $view
     * @return void
     */
    public function redirectPage($view)
    {
        $host = $_SERVER['HTTP_HOST'];
        header("Location: http://$host$view");
        die;
    }
}
