<?php

namespace App\core;

abstract class Model
{
    /**
     * Соединение PDO с базой данных
     * 
     * @var \PDO
     */
    public $pdo;

    protected function __construct()
    {
        $this->pdo = static::DBConnect();
    }

    /**
     * Устанавливает соединение с базой данных
     * 
     * @return \PDO
     */
    static function DBConnect()
    {
        $ini = parse_ini_file('./app/config.ini');
        extract($ini);

        try {
            $pdo = new \PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_user, $db_password);
        } catch (\PDOException $e) {
            $_SESSION['error'] = 'Ошибка подключения к базе данных:' . $e->getMessage();
            header("Location: /main/index", true, 303);
            die;
        }

        return $pdo;
    }

    /**
     * Реализовать метод создания не более одного объекта класса
     */
    abstract static function getInstance();
}
