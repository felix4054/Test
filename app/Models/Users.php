<?php

namespace App\Models;

use App\Services\Db;
use App\Controllers\AppController;

class Users
{
    // объект базы данных
    public $dbUsers;

    //  массив собирающий ошибки валидации
    public $errorsValidate = null;


    /**
     * присваиваем значения объект БД
     * Users constructor.
     */
    public function __construct()
    {
        $this->dbUsers = Db::getUsersTable();
    }

    /**
     * заменяем спецсимволы на сущности
     * @param array $param
     * @return array
     */
    public function screening($param)
    {
        $newParam = [];
        foreach ($param as $key => $value) {
            $newParam[$key] = htmlspecialchars($value);
        }
        return $newParam;
    }

    /**
     * солим пароль и возвращаем хэш
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function makeSaltyPassword($password, $salt)
    {
        return md5($salt . md5($password));
    }

    /**
     * получаем соль для пароля, cookie_key и session_key
     * генерирует хэш строку до 32 символов из рандомного числа, по умолчанию 10 символов
     * @param int $lehgth
     * @return bool|string
     */
    public static function generateSalt($lehgth = 10)
    {
        return substr(md5(mt_rand()), 0, $lehgth);
    }

    /**
     * валидация данных и авторизация пользователя
     * @param array $loginForm
     * @return bool|array
     */
    public function loginUser($loginForm)
    {
        // валидация принятых данных
        $this->validateForLogin($loginForm);

        // если есть ошибоки валидации, передаем массив ошибок
        if ($this->errorsValidate === null) {
            return true;
        } else {
            return $this->errorsValidate;
        }
    }

    /** 
     * Summary of validateForSignIn
     * @param mixed $loginForm
     * @return void
     */
    public function validateForLogin($loginForm)
    {
        // проверка на длину и пустоту
        $loginForm = $this->validateNotNull($loginForm);
        //меняем спецсимволы на html сущности
        $loginForm = $this->screening($loginForm);
        // ищем пользователя в базе и проверяем совпадение паролей
        $user = $this->searchByLogin($loginForm['login']);
        if ($user === false || !$this->equalityPassword($user, $loginForm['password'])) {
            $this->errorsValidate[] = 'ошибка в логине или пароле';
        }
    }

    /**
     * удаление пробелов вначале и вконце, проверка поля на  пустоту
     * @param array $param
     * @return array
     */
    public function validateNotNull($param)
    {
        $newParam = [];
        $nullFlaf = false;
        foreach ($param as $key => $value) {
            $value = trim($value);
            if (strlen($value) == 0) {
                $nullFlaf = true;
            }
            $newParam[$key] = $value;
        }

        if ($nullFlaf) {
            $this->errorsValidate[] = 'пожалуйста, заполните все поля';
            $this->errorLogin = 'Поле не должно быть пустым';
        }
        return $newParam;
    }

    /**
     * ищет по логину и возвращает объект пользователя или false из массива объектов базы
     * @param string $login
     * @return bool|object
     */
    public function searchByLogin($login)
    {
        $resultObject = false;
        $user = $this->dbUsers['user'];
        foreach ($user as $value) {
            if (htmlspecialchars_decode(trim($login)) == trim($value['login'])) {
                $resultObject = $value;
                break;
            }
        }
        return $resultObject;
    }


    /**
     * проверяем соответствие введенного пароля соленому хэшу в базе
     * @param object $user
     * @param string $password
     * @return bool
     */
    public function equalityPassword($user, $password)
    {
        $saltyPassword = $this->makeSaltyPassword($password, $user['salt']);
        if ($saltyPassword == $user['password_hash']) {
            return true;
        }
        return false;
    }

    /**
     * валидация данных и регистраци пользователя
     * @param mixed $data
     * @return bool|null|string[]
     */
    public function registrationUser($data)
    {
        // валидация принятых данных
        $this->validateForRegister($data);
        //создание обработчика ошибок
        // $error = new ErrorHandler();

        // если есть ошибки валидации, передаем массив ошибок
        // если ошибок нет то добавляем поля в объект БД и сохраняем файл
        if ($this->errorsValidate !== null) {
            return $this->errorsValidate;
        } else {
            // генерируем соль
            $salt = self::generateSalt();
            $newUser = $this->dbUsers;
            // print_r($newUser);
            $newUser['user'][] = [
                'login' => $data["login"],
                'email' => $data["email"],
                'name' => $data["name"],
                'salt' => $salt,
                'password_hash' => $this->makeSaltyPassword($data["password"], $salt)
            ];
            //сохраняем нового пользователя
            file_put_contents(Db::getDbPatch()['Users'], json_encode($newUser));

            return true;
        }
    }

    /**
     * валидация полей и проверка пароля для регистрации
     * @param array $data
     */
    public function validateForRegister($data)
    {
        // проверка на длину и пустоту
        $data = $this->validateNotNull($data);
        $this->validateLogin($data['login']);
        $this->errorPassword($data['password']);
        $this->errorName($data['name']);
        // проверяем формат email
        $this->validateEmale($data['email']);
        //меняем спецсимволы на html сущности
        $data = $this->screening($data);
        // сравниваем пароль и подтверждение
        $this->validatePassword($data['password'], $data['confirm_password']);
        // проверяем уникальность логина
        $this->validateUniqueLogin($data['login']);
        // проверяем уникальность емайл
        $this->validateUniqueEmail($data['email']);
    }

    // function t_input($data) {
    //     $data = trim($data);
    //     $data = stripslashes($data);
    //     $data = htmlspecialchars($data);
    //     return $data;
    // }


    public function errorPassword($password)
    {
        // if (strlen($password) < 6) {
        //     return $this->errorsValidate[] = 'пароль должен содержать 6 и более символов';
        // } elseif (!preg_match('/\d/', $password)) {
        //     return $this->errorsValidate[] = 'пароль должен содержать хотя бы одну цифру';
        // } elseif (!preg_match('/\D/', $password)) {
        //     return $this->errorsValidate[] = 'пароль должен содержать хотя бы одну букву';
        // } elseif (preg_match('% +%', $password)) {
        //     return $this->errorsValidate[] = 'пароль не должен содержать пробельных символов';
        // } else {
        //     return true;
        // }

        if (!preg_match("/^(?=.*\d)(?=.*[a-zA-Z])(?!.*\s).*$/", $password) || strlen($password) <= 6) {
            return $this->errorsValidate[] = 'Пароль обязательно должен состоять из букв и цифр длинной не менее 6 символов и не содержать пробелов. ';
        } else {
            return true;
        }

        // $reg = "/[0-9][a-z]|[a-z][0-9]/ui";
        // $passChek = preg_match($reg, str_replace(' ', '', $password));
        // $passCount = strlen(str_replace(' ', '', $password));
        // if ($passChek == 1 && $passCount >= 6) {
        //     return true;
        // } elseif ($passChek != 1 && $passCount >= 6) {
        //     return $this->errorsValidate[] = 'Пароль должен состоять из букв и чисел!';
        // } elseif ($passChek == 1 && $passCount < 6) {
        //     return $this->errorsValidate[] = "Пароль должен быть не менее 6 символов!";
        // } else {
        //     return $this->errorsValidate[] = "Пароль: ввести не менее 6 символов и использовать слова и цифры!";
        // }
    }

    public function errorName($name) //выявляет ошибки в поле Ник
    {
        
        if (!preg_match("/^[a-zA-Z]{2}$/", $name)) {
            return $this->errorsValidate[] = 'Имя должно состоять только из букв длинной 2 символа и не содержать пробелов. ';
        } else {
            return true;
        }

        // $regForName = "/[a-z][a-z]/ui";
        // $nameChek = preg_match($regForName, $name);
        // $nickLength = strlen($name);
        // if ($nameChek != 1 || $nickLength != 2 || ($nameChek != 1 && $nickLength != 2)) {
        //     return $this->errorsValidate[] = "Имя: должно состоять из 2 символов, используйте только буквы!";
        // } else {
        //     return true;
        // }
    }

    /**
     * возвращает номер объекта в массиве объектов БД
     * @param string $login
     * @return bool|int
     */
    public function searchObjectNumberByLogin($login)
    {
        $objectNumber = false;
        $users = $this->dbUsers['user'];
        // print_r($users);
        for ($i = 0; $i <= count($users); $i++) {
            if ($login == (string)$users[$i]['login']) {
                // print_r($login);
                $objectNumber = $i;
                // print_r($objectNumber);

                break;
            }
        }

        return $objectNumber;
    }

    /**
     * Summary of validateLogin
     * @param mixed $login
     * @return bool|string
     */
    public function validateLogin($login)
    {
        // if (strlen($login) < 6) {
        //     return $this->errorsValidate[] = 'логин должен содержать 6 и более символов';
        // } elseif (preg_match('% +%', $login)) {
        //     return $this->errorsValidate[] = 'логин не должен содержать пробельных символов';
        // } else {
        //     return true;
        // }

        if (strlen(($login)) < 6) {
            return $this->errorsValidate[] = 'Длина логина должна составлять не менее 6 символов. ';
        } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $login)) {
            return $this->errorsValidate[] = 'Неверный ввод логина, не используйте пробелы и спецсимволы. ';
        } else {
            return true;
        }

        // $login = trim($login);
        // if (strlen($login) <= 5) {
        //     $this->errorsValidate[] = 'Логин не должен быть короче 6 символов';
        // }

        // return true;
    }

    /**
     * проверяем формат записи email
     * @param string $email
     * @return bool
     */
    public function validateEmale($email)
    {

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errorsValidate[] = '"' . $email . '" не соответствует формату email';
            return false;
        } else {
            return true;
        }

        //'/.+@.+\..+/iu' 

        // if (preg_match('/\A[^@]+@([^@\.]+\.)+[^@\.]+\z/iu', $email) == 0) {
        //     $this->errorsValidate[] = '"' . $email . '" не соответствует формату email';
        //     return false;
        // }
        // return true;
    }

    /**
     * сравниваем пароль и подтверждение
     * @param string $password
     * @param string $confirm_password
     * @return bool
     */
    public function validatePassword($password, $confirm_password)
    {
        if ($password !== $confirm_password) {
            $this->errorsValidate[] = 'пароль и подтверждение не совпадают';
            return false;
        }
        return true;
    }

    /**
     * проверяет уникальность логина
     * @param string $login
     * @return bool
     */
    public function validateUniqueLogin($login)
    {
        $user = $this->searchByLogin($login);
        if ($user !== false) {
            $this->errorsValidate[] = 'пользователь с логином "' . $login . '" уже существует';
            return false;
        }
        return true;
    }

    /**
     * проверяем email на уникальность
     * @param string $email
     * @return bool
     */
    public function validateUniqueEmail($email)
    {
        $user = $this->searchByEmail($email);
        if ($user !== false) {
            $this->errorsValidate[] = 'пользователь с таким email уже есть';
            return false;
        }
        return true;
    }

    /**
     * ищет по email и возвращает объект пользователя или false из массива объектов базы
     * @param string $email
     * @return bool|object
     */
    public function searchByEmail($email)
    {
        $resultObject = false;
        $user = $this->dbUsers['user'];
        foreach ($user as $value) {
            if (trim($email) == trim($value['email'])) {
                $resultObject = $value;
                break;
            }
        }
        return $resultObject;
    }

    /**
     * добавляет пользователю ключ сессии и сохраняет в БД
     * @param mixed $number
     * @param mixed $sessionKey
     * @return mixed
     */
    public function addSessionKey($number, $sessionKey)
    {
        $user = $this->dbUsers;
        $user['user'][$number]['session_key'] = $sessionKey;
        $file = file_put_contents(Db::getDbPatch()['Users'], json_encode($user));
        json_decode($file, true);

        return $this->dbUsers;
    }

    /**
     * сравнивает сессионный-ключ пользователя и ключ в БД
     * @param string $login
     * @param string $sessioKey
     * @return bool
     */
    public function equalitySessionKey($login, $sessioKey)
    {
        $user = $this->searchByLogin($login);
        if ($user['session_key'] == $sessioKey) {
            return true;
        }
        return true;
    }

    /**
     * добавляет пользователю ключ куки и сохраняет в БД
     * @param mixed $number
     * @param mixed $cookieKey
     * @return mixed
     */
    public function addCookieKey($number, $cookieKey)
    {
        $user = $this->dbUsers;
        $user['user'][$number]['cookie_key'] = $cookieKey;
        $file = file_put_contents(Db::getDbPatch()['Users'], json_encode($user));
        json_decode($file, true);

        return $this->dbUsers;
    }

    /**
     * сравнивает куки-ключ пользователя и ключ в БД
     * @param string $login
     * @param string $cookieKey
     * @return bool
     */
    public function equalityCookieKey($login, $cookieKey)
    {
        $user = $this->searchByLogin($login);
        if ($user['cookie_key'] == $cookieKey) {
            return true;
        }
        return false;
    }
}
