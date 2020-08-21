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
        $pdoStat = $this->pdo->prepare($sql);
        $pdoStat->execute();
        // проверяем ошибки
        $errorInfo = $pdoStat->errorInfo();
        if ($errorInfo[1]) {
            $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
        } else {
            $data = $pdoStat->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $data;
    }

    public function getIdFromUrl()
    {
        // берется id из get запроса
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        //проверка на существование id в записях базе данных
        $sqlIsAuth = "SELECT id FROM `authors` WHERE `id` = :id";
        $pdoStat = $this->pdo->prepare($sqlIsAuth);
        $pdoStat->execute(compact('id'));

        if ($pdoStat->rowCount() == 0) {
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
        WHERE `authors`.`id` = :id";
        $pdoStat = $this->pdo->prepare($sql);
        $pdoStat->execute(compact('id'));

        // проверяем ошибки
        $errorInfo = $pdoStat->errorInfo();
        if ($errorInfo[1]) {
            $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
        } else {
            $author = $pdoStat->fetch(\PDO::FETCH_ASSOC);
        }




        return $author;
    }

    function validateName($name, $id = null)
    {
        // валидация на уникальность автора
        $idQuery = ($id) ? "AND `id`<> int $id"  : "";
        $sqlUniqAuthor = "SELECT `id` FROM `authors` WHERE `name` = :name $idQuery";
        $params = compact('name');


        $pdoStat = $this->pdo->prepare($sqlUniqAuthor);
        $pdoStat->execute($params);
        if ($pdoStat->rowCount() > 0) {
            $_SESSION['error'] = 'ошибка ввода - уже есть такой автор';
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
        return $name;
    }

    public function addAuthor()
    {
        if (isset($_POST['submit'])) {
            $name = $_SESSION['name'] = isset($_POST['name']) ? $_POST['name'] : null;

            // валидация
            $name = $this->validateName($name);

            // запрос на добавление автора
            $sqlAuthor = "INSERT INTO authors (`name`) VALUES (:name)";
            $pdoStat = $this->pdo->prepare($sqlAuthor);
            $pdoStat->execute(compact('name'));

            // проверяем ошибки
            $errorInfo = $pdoStat->errorInfo();
            if ($errorInfo[1]) {
                $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
            } else {
                $lastId = $this->pdo->lastInsertId();
                $_SESSION['message'] = "Запись [#$lastId ] добавлена в базу данных!";
                unset($_SESSION['name']);
                header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                die;
            }
        };
    }

    public function updateAuthor($id)
    {
        if (isset($_POST['submit'])) {

            $name = isset($_POST['name']) ? $_POST['name'] : null;

            // валидация
            $name = $this->validateName($name, $id);

            // запрос на обновление автора
            $sqlAuthor = "UPDATE authors SET `name` = :name WHERE `id`=:id";
            $pdoStat = $this->pdo->prepare($sqlAuthor);
            $pdoStat->execute(compact('name', 'id'));

            // проверяем ошибки
            $errorInfo = $pdoStat->errorInfo();
            if ($errorInfo[1]) {
                $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
            } else {
                $_SESSION['message'] = "Запись [#$id ] успешно изменена!";
                header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                die;
            }
        }
    }

    public function deleteAuthor($id)
    {
        if (isset($_POST['submit'])) {

            // запрос на удаление автора
            $delSql = "DELETE FROM authors WHERE `id`= :id";
            $pdoStat = $this->pdo->prepare($delSql);
            $pdoStat->execute(compact('id'));

            $errorInfo = $pdoStat->errorInfo();
            if ($errorInfo[1]) {
                $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
            } else {
                $_SESSION['message'] = "Запись [#$id ] успешно удалена!";
                header("Location: /authors/index", true, 303);
                die;
            }
        };
    }
}
