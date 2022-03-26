<?php

namespace App\Services;

class ErrorHandler
{

    public function errorLogin($login) //выявляет ошибки в поле логин
    {
        $length = strlen(str_replace(' ', '', $login));
        if ($length >= 6) {
            return "OK";
        } else {
            return "Логин должен иметь длину не менее 6 символов!";
        }
    }

    public function errorPassword($password) //выявляет ошибки в поле пароль
    {
        $reg = "/[0-9][a-z]|[a-z][0-9]/ui";
        $passChek = preg_match($reg, str_replace(' ', '', $password));
        $passCount = strlen(str_replace(' ', '', $password));
        if ($passChek == 1 && $passCount >= 6) {
            return "OK";
        } elseif ($passChek != 1 && $passCount >= 6) {
            return "You should use words and numbers!";
        } elseif ($passChek == 1 && $passCount < 6) {
            return "You should enter not less then 6 chars!";
        } else {
            return "You should enter not less then 6 chars and use words and numbers!";
        }

    }

    public function errorName($name) //выявляет ошибки в поле Ник
    {
        $regForName = "/[a-z][a-z]/ui";
        $nameChek = preg_match($regForName, $name);
        $nickLength = strlen($name);
        if ($nameChek != 1 || $nickLength != 2 || ($nameChek != 1 && $nickLength != 2)) {
            return "Вы должны ввести 2 символа, используя только буквы!";
        } else {
            return "OK";
        }
    }

    public function errorMail($email) //выявляет ошибки в поле эмэйл
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return "Неверный формат электронной почты!";
        } else {
            return "OK";
        }
    }
     public function validateEmail($email)
    {
        if (preg_match('/.+@.+\..+/i', $email) == 0) {
            return '"' . $email . '" не соответствует формату email';;
        }
        return "OK";
    }
}