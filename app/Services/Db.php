<?php

namespace App\Services;

class Db
{

    public static function getDbPatch()
    {
        // Получаем параметры подключения из файла
        $paramsPath = ROOT . '/config/db.php';
        return include($paramsPath);
    }

    public static function getUsersTable()
    {
        // Устанавливаем соединение
        $file = file_get_contents(self::getDbPatch()['Users']);
        $users = json_decode($file, true);
        unset($file);

        return $users;
    }


    // public static function create($list)
    // {
    //      $file = file_get_contents(self::getDbPatch()['Users']);
    //      $taskList = json_decode($file, TRUE);
    //      unset($file);
    //      $taskList[] = $list;
    //      file_put_contents(self::getDbPatch()['Users'], json_encode($taskList));
    //      unset($taskList);
    // }

    // public static function read(){
    //     $file = file_get_contents(self::getDbPatch()['Users']);
    //     $taskList = json_decode($file, TRUE);
    //     unset($file);
    //     return $taskList;
    // }



}
