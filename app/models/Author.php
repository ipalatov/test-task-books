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

        if ($result->num_rows == 0) {
            $_SESSION['error'] = 'Автор не найден';
            header("Location: /authors/index");
            die;
        }
        return $id;
    }

    public function getOne()
    {
        $id = $this->getIdFromUrl();

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
            $_SESSION['error'] = 'ошибка ввода - уже есть такой автор';
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
        // экранирование запроса от спец символов
        return $this->mysql->real_escape_string($name);
    }

    public function addAuthor()
    {
        if (isset($_POST['submit'])) {
            $name = $_SESSION['name'] = isset($_POST['name']) ? $_POST['name'] : null;

            // валидация
            $name = $this->validateName($name);

            // запрос на добавление автора
            $sqlAuthor = "INSERT INTO authors (`name`) VALUES ('$name')";
            $this->mysql->query($sqlAuthor);

            // проверяем ошибки
            if (!empty($this->mysql->errno)) {
                $_SESSION['error'] = 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
            } else {
                $lastId = $this->mysql->insert_id;
                $_SESSION['message'] = "Запись [#$lastId ] добавлена в базу данных!";
                unset($_SESSION['name']);
                header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                die;
            }
        };
    }

    public function updateAuthor()
    {
        if (isset($_POST['submit'])) {
            $id = $this->getIdFromUrl();

            $name = isset($_POST['name']) ? $_POST['name'] : null;

            // валидация
            $name = $this->validateName($name, 1);

            // запрос на обновление автора
            $sqlAuthor = "UPDATE authors SET `name` = '$name' WHERE `id`='$id'";
            $this->mysql->query($sqlAuthor);

            // проверяем ошибки
            if (!empty($this->mysql->errno)) {
                $_SESSION['error'] = 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
            } else {
                $_SESSION['message'] = "Запись [#$id ] успешно изменена!";
                header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                die;
            }
        }
    }

    public function deleteAuthor()
    {
        if (isset($_POST['submit'])) {
            $id = $this->getIdFromUrl();

            // запрос на удаление автора
            $delSql = "DELETE FROM authors WHERE `id`= '$id'";
            $this->mysql->query($delSql);
            if (!empty($this->mysql->errno)) {
                $_SESSION['error'] = 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
            } else {
                $_SESSION['message'] = "Запись [#$id ] успешно удалена!";
                header("Location: /authors/index", true, 303);
                die;
            }
        };
    }
}
