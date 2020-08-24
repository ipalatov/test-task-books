<?php

namespace App\models;

use App\core\Model;

class Genre extends Model
{
    /**
     * Экземпляры класса
     * 
     * @var array 
     */
    private static $genreInstances = [];

    /**
     * Закрытие конструктора (protected) от создания объекта через оператор new
     * */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * Закрытие (protected) от клонивания объекта
     */
    protected function __clone()
    {
    }

    /**
     *  Одиночки не должны быть восстанавливаемыми из строк.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * Метод создания не более одного объекта класса
     * 
     * @return Author
     */
    public static function getInstance(): Genre
    {
        $cls = static::class;
        if (!isset(self::$genreInstances[$cls])) {
            self::$genreInstances[$cls] = new static;
        }
        return self::$genreInstances[$cls];
    }

    /**
     * Возвращает список всех жанров
     * 
     * @return array
     */
    public function getIndex()
    {
        $sql = "SELECT `id`, `name` FROM genres";
        $pdoStat = $this->pdo->prepare($sql);
        $pdoStat->execute();

        $data = $pdoStat->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }
}
