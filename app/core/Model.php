<?php

namespace App\core;

use mysqli;

abstract class Model
{
    public $mysql;

    static $db_host;
    static $db_user;
    static $db_password;
    static $db_name;

    protected function __construct()
    {
        $ini = parse_ini_file('./app/config.ini');
        extract($ini);
        static::$db_host = $db_host;
        static::$db_user = $db_user;
        static::$db_password = $db_password;
        static::$db_name = $db_name;

        $this->mysql = static::DBConnect();
    }

    static function DBConnect()
    {
        $mysql = new mysqli();

        $mysql->connect(static::$db_host, static::$db_user, static::$db_password, static::$db_name);
        if ($mysql->connect_errno) {
            echo 'Ошибка подключения к базе данных (' . $mysql->connect_errno . '): ' . $mysql->connect_error;
            exit();
        }

        $mysql->set_charset('utf8');

        return $mysql;
    }

    abstract public function getIndex();
}
