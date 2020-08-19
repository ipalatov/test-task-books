<?php

namespace App\models;

use App\core\Model;

class Author extends Model
{
    private static $authorInstances = [];

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
    public static function getAuthorInstance(): Author
    {
        $cls = static::class;
        if (!isset(self::$authorInstances[$cls])) {
            self::$authorInstances[$cls] = new static;
        }
        return self::$authorInstances[$cls];
    }



    public function getIndex()
    {
        $sql = "SELECT `id`, `name`, (select count(*) from `book_author` where `author_id`=`authors`.id) as
         bookNum 
         FROM `authors`
         ORDER BY `id`";

        $result = $this->mysql->query($sql);
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $data;
    }

    public function getIdFromUrl()
    {
        // берется id из get запроса
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        //проверка на существование id в записях базе данных
        $sqlIsAuth = "SELECT id FROM `authors` WHERE `id` = '$id'";
        $result = $this->mysql->query($sqlIsAuth);

        if ($result->num_rows > 0) {
            return $id;
        }

        return null;
    }

    public function getOne()
    {
        $id = $this->getIdFromUrl();
        if (empty($id)) {
            echo 'Автор не найден';
            die;
        }

        // запрос на одного автора
        $sql = "SELECT `id`, `name` FROM `authors`
        WHERE `authors`.`id` = '$id'";

        $result = $this->mysql->query($sql);
        $authors = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $author = $authors[0];

        return $author;
    }

    function validateName($name, $repNum = 0)
    {
        // валидация на уникальность автора
        $sqlUniqAuthor = "SELECT `id` FROM `authors` WHERE `name` = '$name'";
        $result = $this->mysql->query($sqlUniqAuthor);
        if ($result->num_rows > $repNum) {
            echo 'ошибка ввода - уже есть такой автор';
            die;
        }
        // экранирование запроса от спец символов
        return $this->mysql->real_escape_string($name);
    }

    public function addAuthor()
    {
        if (isset($_POST['submit'])) {
            $name = isset($_POST['name']) ? $_POST['name'] : null;

            // валидация
            $name = $this->validateName($name);

            // запрос на добавление автора
            $sqlAuthor = "INSERT INTO authors (`name`) VALUES ('$name')";
            $this->mysql->query($sqlAuthor);

            // проверяем ошибки
            if (!empty($this->mysql->errno)) {
                echo 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
            } else {
                echo 'Запись успешно добавлена в базу данных!';
            }
        };
    }

    public function updateAuthor()
    {
        if (isset($_POST['submit'])) {
            $id = $this->getIdFromUrl();
            if (empty($id)) {
                echo 'Автор не найден';
                die;
            }

            $name = isset($_POST['name']) ? $_POST['name'] : null;

            // валидация
            $name = $this->validateName($name, 1);

            // запрос на обновление автора
            $sqlAuthor = "UPDATE authors SET `name` = '$name' WHERE `id`='$id'";
            $this->mysql->query($sqlAuthor);

            // проверяем ошибки
            if (!empty($this->mysql->errno)) {
                echo 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
            } else {
                echo 'Запись успешно изменена!';
                header("Refresh:0");
            }
        }
    }
    public function deleteAuthor()
    {
        if (isset($_POST['submit'])) {
            $id = $this->getIdFromUrl();
            if (empty($id)) {
                echo 'Автор не найден';
                die;
            }
            // запрос на удаление автора
            $delSql = "DELETE FROM authors WHERE `id`= '$id'";
            $this->mysql->query($delSql);
            if (!empty($this->mysql->errno)) {
                echo 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
            } else {
                echo 'Запись успешно удалена!';
            }
        };
    }
}
