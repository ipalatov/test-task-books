<?php

namespace App\models;

use App\core\Model;
use App\core\Validator;

class Author extends Model
{
    /**
     * Экземпляры класса
     * 
     * @var array 
     */
    private static $authorInstances = [];

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
    public static function getInstance(): Author
    {
        $cls = static::class;
        if (!isset(self::$authorInstances[$cls])) {
            self::$authorInstances[$cls] = new static;
        }
        return self::$authorInstances[$cls];
    }

    /**
     * Возвращает список всех авторов и количество книг каждого
     * 
     * @return array
     */
    public function getIndex()
    {
        $sql = "SELECT `id`, `name`, (select count(*) from `book_author` where `author_id`=`authors`.id) as
         bookNum 
         FROM `authors`
         ORDER BY `id`";
        $pdoStat = $this->pdo->prepare($sql);
        $pdoStat->execute();

        $errorInfo = $pdoStat->errorInfo();
        if ($errorInfo[1]) {
            $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }

        $authors = $pdoStat->fetchAll(\PDO::FETCH_ASSOC);

        return $authors;
    }

    /**
     * Получает id автора из GET запроса и осуществляет проверку на существование такого в базе данных
     * 
     * @return int
     */
    public function getIdFromUrl()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

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

    /**
     * Возвращает из базы данных одну запись (автора) по id из GET запроса
     * 
     * @return array
     */
    public function getOne()
    {
        $id = $this->getIdFromUrl();

        $sql = "SELECT `id`, `name` FROM `authors`
        WHERE `authors`.`id` = :id";
        $pdoStat = $this->pdo->prepare($sql);
        $pdoStat->execute(compact('id'));

        $errorInfo = $pdoStat->errorInfo();
        if ($errorInfo[1]) {
            $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }

        $author = $pdoStat->fetch(\PDO::FETCH_ASSOC);

        return $author;
    }

    /**
     * Проверяет строку на длину, а также уникальность в БД, с помощью абстрактного класса Validator
     * 
     * @param string $name
     * @param int|null $id
     * @return string Та же строка, если прошла все проверки
     */
    function validateName(string $name, int $id = null)
    {
        Validator::checkStringMin($name, 3);
        Validator::checkStringMax($name, 100);

        Validator::checkUniqField('authors', 'name', $name, $id);

        return $name;
    }

    /**
     * Получает данные из формы, записывает в сессию, валидирует и добавляет в БД
     * 
     * @return void 
     */
    public function addAuthor()
    {
        if (isset($_POST['submit'])) {
            $name = $_SESSION['name'] = isset($_POST['name']) ? (string) $_POST['name'] : null;

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
                header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                die;
            }
            $lastId = $this->pdo->lastInsertId();
            $_SESSION['message'] = "Запись [#$lastId ] добавлена в базу данных!";
            unset($_SESSION['name']);
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
    }

    /**
     * Получает данные из формы, валидирует и обновляет в БД по $id автора
     * 
     * @param int $id id автора
     * @return void 
     */
    public function updateAuthor($id)
    {
        if (isset($_POST['submit'])) {

            $name = isset($_POST['name']) ? (string) $_POST['name'] : null;

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
                header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                die;
            }
            $_SESSION['message'] = "Запись [#$id ] успешно изменена!";
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
    }
    
    /**
     * Удаляет автора из БД по $id
     * 
     * @param int $id id автора
     * @return void 
     */
    public function deleteAuthor($id)
    {
        if (isset($_POST['submit'])) {

            $delSql = "DELETE FROM authors WHERE `id`= :id";
            $pdoStat = $this->pdo->prepare($delSql);
            $pdoStat->execute(compact('id'));

            $errorInfo = $pdoStat->errorInfo();
            if ($errorInfo[1]) {
                $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
                header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                die;
            }
            $_SESSION['message'] = "Запись [#$id ] успешно удалена!";
            header("Location: /authors/index", true, 303);
            die;
        }
        if (isset($_POST['noDelete'])) {
            header("Location: /authors/show?id=$id", true, 303);
            die;
        }
    }
}
