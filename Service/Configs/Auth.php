<?php

include_once 'Service/Configs/DB.php';

class Auth
{
    /**
     * Простая авторизация по username password
     * Если в таблице users и пароли совпадают, то впускаем
     */
    static function isNotValid($password, $username)
    {
        $result = DB::findByUsername($username);
        if ($result['password'] == $password) {
            return false;
        }
        return true;
    }
}