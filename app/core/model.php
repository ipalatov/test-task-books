<?php

namespace App\core;

use mysqli;

abstract class Model
{
    public $mysql;

    protected function __construct()
    {
        $this->mysql = static::DBConnect();
    }

    static function DBConnect()
    {
        $mysql = new mysqli();
        $ini = parse_ini_file('./app/core/config.ini');

        $mysql->connect($ini['db_host'], $ini['db_user'], $ini['db_password'], $ini['db_name']);
        if ($mysql->connect_errno) {
            echo 'Ошибка подключения к базе данных (' . $mysql->connect_errno . '): ' . $mysql->connect_error;
            exit();
        }

        $mysql->set_charset('utf8');

        return $mysql;
    }

    abstract public function getIndex();
}
