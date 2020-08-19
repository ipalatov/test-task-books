<?php

namespace App\models;

use App\core\Model;

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
    public static function getBookInstance(): Book
    {
        $cls = static::class;
        if (!isset(self::$bookInstances[$cls])) {
            self::$bookInstances[$cls] = new static;
        }
        return self::$bookInstances[$cls];
    }



    static function getQueryAuthors() //получение фильтров, запись их в сессию, составление переменных для запроса
    {
        if (isset($_POST['author'])) {
            $_SESSION['author'] = $_POST['author'];
        }
        $filterAuthors = isset($_SESSION['author']) ? implode(", ", $_SESSION['author']) : 0;
        $queryAuthors = $filterAuthors > 0 ? " AND authors.id IN ($filterAuthors)" : "";
        return $queryAuthors;
    }

    static function getQueryGenres()
    {
        if (isset($_POST['genre'])) {
            $_SESSION['genre'] = $_POST['genre'];
        }
        $filterGenres = isset($_SESSION['genre']) ? implode(", ", $_SESSION['genre']) : 0;
        $queryGenres = $filterGenres > 0 ? " AND genre_id IN ($filterGenres)" : "";
        return $queryGenres;
    }

    static function getQueryYears()
    {
        if (isset($_POST['startYear'])) {
            $_SESSION['startYear'] = $_POST['startYear'];
        }

        if (isset($_POST['endYear'])) {
            $_SESSION['endYear'] = $_POST['endYear'];
        }
        $startYear = isset($_SESSION['startYear']) ? (int) $_SESSION['startYear'] : 0;
        $endYear = isset($_SESSION['endYear']) ? (int) $_SESSION['endYear'] : 0;
        $queryYears = ($endYear > 0) && ($endYear >= 0) ? " AND year BETWEEN $startYear AND $endYear" : "";
        return $queryYears;
    }

    public function resetFilters() // сброс фильтров из сессии
    {
        if (isset($_POST['reset_filter'])) {
            unset($_SESSION['author']);
            unset($_SESSION['genre']);
            unset($_SESSION['startYear']);
            unset($_SESSION['endYear']);
            header('Refresh:0');
            unset($_POST['reset_filter']);
        }
    }

    // получение общего количества записей по условиям фильтра
    public function getTotal()
    {
        session_start();
        // переменные для запроса по условиям фильтра
        $queryAuthors = static::getQueryAuthors();
        $queryGenres = static::getQueryGenres();
        $queryYears = static::getQueryYears();

        $sql = "SELECT DISTINCT books.id FROM books
        JOIN book_author ON (books.id = book_author.book_id)
        JOIN authors ON (authors.id = book_author.author_id)
        JOIN genres ON (books.genre_id = genres.id)
        WHERE books.id > 0 $queryAuthors $queryGenres $queryYears";

        $result = $this->mysql->query($sql);

        $result = $this->mysql->query($sql);
        if (!$result) {
            $total = 0;
        } else {
            $total = $result->num_rows;
        }

        return $total;
    }


    public function getIndex($offset = null, $limit = null)
    {

        // сортировка
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

        $result = $this->mysql->query($sql);
        if (!$result) {
            echo 'Книг не найдено';
            $books = [];
        } else {
            $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        $this->resetFilters();

        return $books;
    }

    public function getIdFromUrl()
    {
        // берет id из get запроса
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        //проверка на существование id в записях базе данных
        $sqlIsBook = "SELECT id FROM `books` WHERE `id` = '$id'";
        $result = $this->mysql->query($sqlIsBook);

        if ($result->num_rows > 0) {
            return $id;
        }

        return null;
    }

    public function getOne()
    {
        $id = $this->getIdFromUrl();
        if (empty($id)) {
            echo 'Книга не найдена';
            die;
        }

        // запрос на одну книгу с конкатенацией по столбцу authors.name
        $sql = "SELECT books.id, books.title, genres.name as genre, books.year, GROUP_CONCAT(authors.name SEPARATOR ', ')
        as author FROM book_author
        JOIN books ON (books.id = book_author.book_id)
        JOIN authors ON (authors.id = book_author.author_id)
        JOIN genres ON (books.genre_id = genres.id)
        WHERE books.id = '$id'";

        $result = $this->mysql->query($sql);
        $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $book = $books[0];

        return $book;
    }

    function validateTitle($title, $repNum = 0)
    {
        // валидация на длину многобайтовой строки
        if (mb_strlen($title) > 250) {
            echo 'ошибка ввода - название книги содержит более 250 символов';
            die;
        }
        // валидация на уникальность
        $sqlUniqBook = "SELECT id FROM books WHERE title = '$title'";
        $result = $this->mysql->query($sqlUniqBook);
        if ($result->num_rows > $repNum) {
            echo 'ошибка ввода - уже есть такое название книги';
            die;
        }
        // экранирование запроса от спец символов
        return $this->mysql->real_escape_string($title);
    }

    function validateYear($year)
    {
        // валидация на год издания, д.б. не больше текущего
        if ($year > date('Y')) {
            echo 'ошибка ввода - год издания больше текущего';
            die;
        }

        return $year;
    }

    public function addBook()
    {
        if (isset($_POST['submit'])) {
            $title = isset($_POST['title']) ? $_POST['title'] : null;
            $genre_id = isset($_POST['genre_id']) ? (int) $_POST['genre_id'] : null;
            $authors_id = isset($_POST['authors_id']) ? $_POST['authors_id'] : null;
            $year = isset($_POST['year']) ? (int) $_POST['year'] : null;

            // валидация
            $title = $this->validateTitle($title);
            $year = $this->validateYear($year);

            // добавляем книгу
            $sqlBook = "INSERT INTO books (`title`, `genre_id`, `year`) VALUES ('$title', '$genre_id' , '$year')";
            $this->mysql->query($sqlBook);

            // проверяем ошибки и получаем последний id в таблице книги
            if (!empty($this->mysql->errno)) {
                echo 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
            } else {
                $lastId = $this->mysql->insert_id;

                // заносим в связующую таблицу последний id книги и id каждого автора из формы
                foreach ($authors_id as $author_id) {
                    $sqlBookAuthor = "INSERT INTO book_author (`book_id`, `author_id`) VALUES ('$lastId', '$author_id')";
                    $this->mysql->query($sqlBookAuthor);
                }

                // проверяем на ошибку и сообщаем успешность
                if (!empty($this->mysql->errno)) {
                    echo 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
                } else {
                    echo 'Запись успешно добавлена в базу данных!';
                }
            }
        }
    }

    public function updateBook()
    {
        if (isset($_POST['submit'])) {
            $id = $this->getIdFromUrl();
            if (empty($id)) {
                echo 'Книга не найдена';
                die;
            }

            $title = isset($_POST['title']) ? $_POST['title'] : null;
            $genre_id = isset($_POST['genre_id']) ? (int) $_POST['genre_id'] : null;
            $authors_id = isset($_POST['authors_id']) ? $_POST['authors_id'] : null;
            $year = isset($_POST['year']) ? (int) $_POST['year'] : null;

            // валидация
            $title = $this->validateTitle($title, 1);
            $year = $this->validateYear($year);

            // обновляем книгу
            $sqlBook = "UPDATE books SET `title`='$title', `genre_id`='$genre_id', `year`='$year' WHERE `id`='$id'";
            $this->mysql->query($sqlBook);

            // проверяем ошибки 
            if (!empty($this->mysql->errno)) {
                echo 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
            } else {

                // удаляем в связующей таблице записи по id книги и добавляем новые записи id каждого автора из формы
                $delSql = "DELETE FROM book_author WHERE `book_id`= '$id'";
                $this->mysql->query($delSql);
                foreach ($authors_id as $author_id) {
                    $sqlBookAuthor = "INSERT INTO book_author (`book_id`, `author_id`) VALUES ('$id', '$author_id')";
                    $this->mysql->query($sqlBookAuthor);
                }

                // проверяем на ошибку и сообщаем успешность
                if (!empty($this->mysql->errno)) {
                    echo 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
                } else {
                    echo 'Запись успешно изменена!';
                    header("Refresh:0");
                }
            }
        };
    }
    public function deleteBook()
    {
        if (isset($_POST['submit'])) {
            $id = $this->getIdFromUrl();
            if (empty($id)) {
                echo 'Книга не найдена';
                die;
            }
            // удаление книги и сообщение об успешности или ошибке
            $delSql = "DELETE FROM books WHERE `id`= '$id'";
            $this->mysql->query($delSql);
            if (!empty($this->mysql->errno)) {
                echo 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
            } else {
                echo 'Запись успешно удалена!';
            }
        }
    }

    public function getBooksByAuthor($authorId)
    {
        if (empty($authorId)) {
            echo 'Автор не найден';
            die;
        }
        // получение списка книг по id автора
        $sql = "SELECT books.id, books.title, genres.name as genre, books.year FROM book_author
        JOIN books ON (books.id = book_author.book_id)
        JOIN authors ON (authors.id = book_author.author_id)
        JOIN genres ON (books.genre_id = genres.id)
        WHERE authors.id = '$authorId'";

        $result = $this->mysql->query($sql);

        $books = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $books;
    }
}
