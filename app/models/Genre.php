<?php

namespace App\models;

use App\core\Model;

class Genre extends Model
{

    private static $genreInstances = [];

    protected function __construct()
    {
        parent::__construct();
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
    // синглтон для создания не более одного эксземпляра модели
    public static function getGenreInstance(): Genre
    {
        $cls = static::class;
        if (!isset(self::$genreInstances[$cls])) {
            self::$genreInstances[$cls] = new static;
        }
        return self::$genreInstances[$cls];
    }

    public function getIndex()
    {
        $sql = "SELECT *  FROM genres";

        $result = $this->mysql->query($sql);
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $data;
    }
}