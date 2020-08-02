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
        $mysql->connect('localhost', 'ivan', '2222', 'test_books');
        if ($mysql->connect_errno) {
            echo 'Ошибка подключения к базе данных (' . $mysql->connect_errno . '): ' . $mysql->connect_error;
            exit();
        }

        $mysql->set_charset('utf8');

        return $mysql;
    }
    
    abstract public function getIndex();    
}
