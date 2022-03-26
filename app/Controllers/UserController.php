<?php

namespace App\Controllers;

use App\Models\Cookie;
use App\Models\Session;
use App\Models\Users;
use App\Services\Router;

class UserController extends AppController
{

    //  /**
    //  *  титульная страница.
    //  *  проверяет авторизацию, и формирует представление
    //  *  либо формы авторизации и регистрации, либо страницу приветствия
    //  * @return bool
    //  */
    // public function home()
    // {
    //     //если гость то показываем формы
    //     if (self::isGuest()) {
    //         return Router::redirect('/login');
    //     }

    //     // если авторизован, титульная страница
    //     return Router::redirect('/profile');
    // }

    // /**
    //  * страница приветствия
    //  * @return bool
    //  */
    // public function profile()
    // {
    //     return Router::redirect('/profile');
    // } 

   
    /**
     * авторизаци, метод вызывается через ajax
     * возвращает json
     */
    public function login()
    {
        $users = new Users();
        // валидация полей и проверка соответствия пароля
        $activateUser = $users->loginUser($_POST['data']);
        // // print_r($activateUser);
        if ($activateUser === true) {
            // получаем номер пользователя в массиве
            $numberUser = (int)$users->searchObjectNumberByLogin($_POST['data']['login']);
            // print_r($numberUser);
            // если чекбокс есть, то создаём куки, если нет то только сессию
            if (isset($_POST['check'])) {
                Cookie::create($users, $numberUser);
            }
            // создаем сессию и пишем в базу
            Session::create($users, $numberUser);

            self::responseJson([
                'success' => true,
                'message' => 'добро пожаловать',
            ]);
            
            
            // Router::redirect('/profile');
            
        } else {
            // Router::error('500');
            // print_r($activateUser);
           
            self::responseJson([
                'success' => false,
                'message' => implode('<br>', $activateUser),
            ]);
        }
        
    }

    public function register()
    {
        $users = new Users();
        $registerUser = $users->registrationUser($_POST['data']);
        // print_r($registerUser);
        if ($registerUser === true) {

            self::responseJson([
                'success' => true,
                'message' => 'спасибо за регистрацию, теперь вы можете авторизоваться',
            ]);
            
            
        } else {
            // Router::error('500');
            // print_r($registerUser);
            self::responseJson([
                'success' => false,
                'message' => implode('<br>', $registerUser),
            ]);
            
        }
    }

    // public function logout()
    // {
    //     unset($_SESSION["name"]);
    //     Router::redirect('/home');
    // }

    /**
     * ломаем куки и сессию и переходим на титульную страницу
     */
    public function logout()
    {
        // сломать сессию
        Session::delete();
        // сломать куки
        Cookie::destroy();
        Router::redirect('/login');
    }
}
