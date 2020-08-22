<?php

namespace App\models;

use App\core\Model;
use App\core\Validator;
use PDO;

class Book extends Model
{
    private static $bookInstances = [];

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
    public static function getInstance(): Book
    {
        $cls = static::class;
        if (!isset(self::$bookInstances[$cls])) {
            self::$bookInstances[$cls] = new static;
        }
        return self::$bookInstances[$cls];
    }



    static function getQueryAuthors() //получение фильтров, запись их в сессию, составление переменных для запроса
    {
        if (isset($_POST['authorFilter'])) {
            $_SESSION['authorFilter'] = $_POST['authorFilter'];
            $filterAuthors = implode(", ", $_SESSION['authorFilter']);
        } else {
            $filterAuthors = 0;
            unset($_SESSION['authorFilter']);
        }
        $queryAuthors = $filterAuthors > 0 ? " AND authors.id IN ($filterAuthors)" : "";
        return $queryAuthors;
    }

    static function getQueryGenres()
    {
        if (isset($_POST['genreFilter'])) {
            $_SESSION['genreFilter'] = $_POST['genreFilter'];
            $filterGenres = implode(", ", $_SESSION['genreFilter']);
        } else {
            $filterGenres = 0;
            unset($_SESSION['genreFilter']);
        }

        $queryGenres = $filterGenres > 0 ? " AND genre_id IN ($filterGenres)" : "";
        return $queryGenres;
    }

    static function getQueryYears()
    {
        if (isset($_POST['startYearFilter']) && $_POST['startYearFilter'] > 0) {
            $_SESSION['startYearFilter'] = $_POST['startYearFilter'];
            $startYear = (int) $_SESSION['startYearFilter'];
        } else {
            $startYear = 0;
            unset($_SESSION['startYearFilter']);
        }

        if (isset($_POST['endYearFilter'])) {
            $_SESSION['endYearFilter'] = $_POST['endYearFilter'];
            $endYear = (int) $_SESSION['endYearFilter'];
        } else {
            $endYear = 0;
            unset($_SESSION['endYearFilter']);
        }

        if ($startYear > 0 && $endYear > 0) {
            $queryYears = " AND `year` BETWEEN $startYear AND $endYear";
        } elseif ($startYear == 0 && $endYear > 0) {
            $queryYears = " AND `year` <= $endYear";
        } elseif ($startYear > 0 && $endYear == 0) {
            $queryYears = " AND `year` >= $startYear";
        } else {
            $queryYears = "";
        }

        return $queryYears;
    }

    public function resetFilters() // сброс фильтров из сессии
    {
        if (isset($_POST['reset_filter'])) {
            unset($_SESSION['authorFilter']);
            unset($_SESSION['genreFilter']);
            unset($_SESSION['startYearFilter']);
            unset($_SESSION['endYearFilter']);
            unset($_POST['reset_filter']);
            header('Refresh:0');
            die;
        }
    }

    // получение общего количества записей по условиям фильтра
    public function getTotal()
    {
        // переменные для запроса по условиям фильтра
        $queryAuthors = static::getQueryAuthors();
        $queryGenres = static::getQueryGenres();
        $queryYears = static::getQueryYears();

        $sql = "SELECT COUNT(DISTINCT `books`.`id`) AS `count` FROM `books`
        JOIN `book_author` ON (`books`.`id` = `book_author`.`book_id`)
        JOIN `authors` ON (`authors`.`id` = `book_author`.`author_id`)
        JOIN `genres` ON (`books`.`genre_id` = `genres`.id)
        WHERE `books`.`id` > 0 $queryAuthors $queryGenres $queryYears";

        $pdoStat = $this->pdo->prepare($sql);
        $pdoStat->execute();

        // проверка ошибок
        $errorInfo = $pdoStat->errorInfo();
        if ($errorInfo[1]) {
            $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
        $total = $pdoStat->fetch(\PDO::FETCH_ASSOC);

        return $total['count'];
    }

    static function getSorting()
    {
        $sorting = 'book_id ASC';

        if (isset($_GET['sort'])) {
            switch ($_GET['sort']) {
                case 'title';
                    $sorting = 'title ASC';
                    break;
                case 'year';
                    $sorting = 'year ASC';
                    break;
                default:
                    $sorting = 'book_id ASC';
                    break;
            }
        }
        return $sorting;
    }


    public function getIndex($offset = null, $limit = null)
    {
        // сортировка
        $sorting = static::getSorting();

        // переменные для запроса по условиям фильтра
        $queryAuthors = static::getQueryAuthors();
        $queryGenres = static::getQueryGenres();
        $queryYears = static::getQueryYears();

        // запрос на список книг с конкатенацией по столбцу authors.name, с условиями по фильтру и сортировкой
        $sql = "SELECT books.id, books.title, genres.name as genre, books.year, GROUP_CONCAT(authors.name SEPARATOR ', ')
        as author FROM book_author
        JOIN books ON (books.id = book_author.book_id)
        JOIN authors ON (authors.id = book_author.author_id)
        JOIN genres ON (books.genre_id = genres.id)
        WHERE books.id > 0 $queryAuthors $queryGenres $queryYears
        GROUP BY book_id
        ORDER BY $sorting
        LIMIT $offset, $limit";

        $pdoStat = $this->pdo->prepare($sql);
        $pdoStat->execute();

        $errorInfo = $pdoStat->errorInfo();
        if ($errorInfo[1]) {
            $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }

        if ($pdoStat->rowCount() == 0) {
            $_SESSION['error'] = 'Книг не найдено';
            $books = [];
        } else {
            $books = $pdoStat->fetchAll(\PDO::FETCH_ASSOC);
        }

        $this->resetFilters();

        return $books;
    }

    public function getIdFromUrl()
    {
        // берет id из get запроса
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        //проверка на существование id в записях базе данных
        $sql = "SELECT id FROM `books` WHERE `id` = :id";
        $pdoStat = $this->pdo->prepare($sql);
        $pdoStat->execute(compact('id'));

        $errorInfo = $pdoStat->errorInfo();
        if ($errorInfo[1]) {
            $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }

        if ($pdoStat->rowCount() == 0) {
            $_SESSION['error'] = "Книга не найдена";
            header("Location: /books/index");
            die;
        }
        return $id;
    }

    public function getOne()
    {
        $id = $this->getIdFromUrl();

        // запрос на одну книгу с конкатенацией по столбцу authors.name
        $sql = "SELECT books.id, books.title, genres.name as genre, books.year, GROUP_CONCAT(authors.name SEPARATOR ', ')
        as author FROM book_author
        JOIN books ON (books.id = book_author.book_id)
        JOIN authors ON (authors.id = book_author.author_id)
        JOIN genres ON (books.genre_id = genres.id)
        WHERE books.id = :id";

        $pdoStat = $this->pdo->prepare($sql);
        $pdoStat->execute(compact('id'));

        $errorInfo = $pdoStat->errorInfo();
        if ($errorInfo[1]) {
            $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }

        $book = $pdoStat->fetch(\PDO::FETCH_ASSOC);

        return $book;
    }

    function validateTitle($title, int $id = null)
    {
        // валидация мин и макс длину строки
        Validator::checkStringMin($title, 3);
        Validator::checkStringMax($title, 250);

        // валидация на уникальность 
        Validator::checkUniqField('books', 'title', $title, $id);

        return $title;
    }

    function validateYear($year)
    {
        // валидация на год издания, д.б. не больше текущего
        Validator::checkCurrentYear($year);

        return $year;
    }

    public function resetFormData() // сброс фильтров из сессии
    {
        if (isset($_POST['reset'])) {
            unset($_SESSION['title']);
            unset($_SESSION['authors_id']);
            unset($_SESSION['genre_id']);
            unset($_SESSION['year']);
            unset($_POST['reset_filter']);
            header('Refresh:0');
            die;
        }
    }
    public function addBook()
    {
        if (isset($_POST['submit'])) {
            $title = $_SESSION['title'] = isset($_POST['title']) ? (string) $_POST['title'] : null;
            $authors_id = $_SESSION['authors_id'] = isset($_POST['authors_id']) ? (array) $_POST['authors_id'] : null;
            $genre_id = $_SESSION['genre_id'] = isset($_POST['genre_id']) ? (int) $_POST['genre_id'] : null;
            $year = $_SESSION['year'] = isset($_POST['year']) ? (int) $_POST['year'] : null;

            // валидация
            $title = $this->validateTitle($title);
            $year = $this->validateYear($year);

            // добавляем книгу
            $sqlBook = "INSERT INTO books (`title`, `genre_id`, `year`) VALUES (:title, :genre_id , :year)";
            $pdoStat = $this->pdo->prepare($sqlBook);
            $pdoStat->execute(compact('title', 'genre_id', 'year'));
            // проверяем ошибки 
            $errorInfo = $pdoStat->errorInfo();
            if ($errorInfo[1]) {
                $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
                header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                die;
            }
            // получаем последний id в таблице книги
            $lastId = $this->pdo->lastInsertId();

            // заносим в связующую таблицу последний id книги и id каждого автора из формы
            foreach ($authors_id as $author_id) {
                $sqlBookAuthor = "INSERT INTO book_author (`book_id`, `author_id`) VALUES (:lastId, :author_id)";
                $pdoStatA = $this->pdo->prepare($sqlBookAuthor);
                $pdoStatA->execute(compact('lastId', 'author_id'));

                $errorInfoA = $pdoStatA->errorInfo();
                if ($errorInfoA[1]) {
                    $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfoA[1] . ' - ' . $errorInfoA[2];
                    header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                    die;
                }
            }

            $_SESSION['message'] = "Запись [#$lastId] успешно добавлена в базу данных!";
            unset($_SESSION['title']);
            unset($_SESSION['authors_id']);
            unset($_SESSION['genre_id']);
            unset($_SESSION['year']);
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
        $this->resetFormData();
    }

    public function updateBook($id)
    {
        if (isset($_POST['submit'])) {

            $title = isset($_POST['title']) ? (string) $_POST['title'] : null;
            $authors_id = isset($_POST['authors_id']) ? (array) $_POST['authors_id'] : null;
            $genre_id = isset($_POST['genre_id']) ? (int) $_POST['genre_id'] : null;
            $year = isset($_POST['year']) ? (int) $_POST['year'] : null;

            // валидация
            $title = $this->validateTitle($title, $id);
            $year = $this->validateYear($year);

            // обновляем книгу
            $sqlBook = "UPDATE books SET `title`=:title, `genre_id`=:genre_id, `year`=:year WHERE `id`=:id";
            $pdoStat = $this->pdo->prepare($sqlBook);
            $pdoStat->execute(compact('title', 'genre_id', 'year', 'id'));
            // проверяем ошибки 
            $errorInfo = $pdoStat->errorInfo();
            if ($errorInfo[1]) {
                $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
                header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                die;
            }

            // удаляем в связующей таблице записи по id книги и добавляем новые записи id каждого автора из формы
            $delSql = "DELETE FROM book_author WHERE `book_id`= :id";
            $pdoStat = $this->pdo->prepare($delSql);
            $pdoStat->execute(compact('id'));
            // проверяем ошибки 
            $errorInfo = $pdoStat->errorInfo();
            if ($errorInfo[1]) {
                $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
                header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                die;
            }
            foreach ($authors_id as $author_id) {
                $sqlBookAuthor = "INSERT INTO book_author (`book_id`, `author_id`) VALUES (:id, :author_id)";
                $pdoStatA = $this->pdo->prepare($sqlBookAuthor);
                $pdoStatA->execute(compact('id', 'author_id'));

                $errorInfoA = $pdoStatA->errorInfo();
                if ($errorInfoA[1]) {
                    $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfoA[1] . ' - ' . $errorInfoA[2];
                    header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                    die;
                }
            }
            $_SESSION['message'] = "Запись [#$id] успешно изменена!";
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        };
    }
    public function deleteBook($id)
    {
        if (isset($_POST['submit'])) {

            // удаление книги и сообщение об успешности или ошибке
            $delSql = "DELETE FROM books WHERE `id`= :id";
            $pdoStat = $this->pdo->prepare($delSql);
            $pdoStat->execute(compact('id'));

            $errorInfo = $pdoStat->errorInfo();
            if ($errorInfo[1]) {
                $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
                header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                die;
            }

            $_SESSION['message'] = "Запись [#$id] успешно удалена!";
            header("Location: /books/index", true, 303);
            die;
        }
    }

    public function getBooksByAuthor($authorId)
    {
        // получение списка книг по id автора
        $sql = "SELECT books.id, books.title, genres.name as genre, books.year FROM book_author
        JOIN books ON (books.id = book_author.book_id)
        JOIN authors ON (authors.id = book_author.author_id)
        JOIN genres ON (books.genre_id = genres.id)
        WHERE authors.id = :authorId";
        $pdoStat = $this->pdo->prepare($sql);
        $pdoStat->execute(compact('authorId'));

        $books = $pdoStat->fetchAll(\PDO::FETCH_ASSOC);

        return $books;
    }
}
