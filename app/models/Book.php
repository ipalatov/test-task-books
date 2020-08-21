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
            header('Refresh:0');
            unset($_POST['reset_filter']);
        }
    }

    // получение общего количества записей по условиям фильтра
    public function getTotal()
    {
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

        $result = $this->mysql->query($sql);
        if (!$result) {
            $_SESSION['error'] = 'Книг не найдено';
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

        if ($result->num_rows == 0) {
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
            $_SESSION['error'] = 'ошибка ввода - название книги содержит более 250 символов';
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
        // валидация на уникальность
        $sqlUniqBook = "SELECT id FROM books WHERE title = '$title'";
        $result = $this->mysql->query($sqlUniqBook);
        if ($result->num_rows > $repNum) {
            $_SESSION['error'] = 'ошибка ввода - уже есть такое название книги';
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
        // экранирование запроса от спец символов
        return $this->mysql->real_escape_string($title);
    }

    function validateYear($year)
    {
        // валидация на год издания, д.б. не больше текущего
        if ($year > date('Y')) {
            $_SESSION['error'] = 'ошибка ввода - год издания больше текущего';
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }

        return $year;
    }

    public function addBook()
    {
        if (isset($_POST['submit'])) {
            $title = $_SESSION['title'] = isset($_POST['title']) ? $_POST['title'] : null;
            $authors_id = $_SESSION['authors_id'] = isset($_POST['authors_id']) ? $_POST['authors_id'] : null;
            $genre_id = $_SESSION['genre_id'] = isset($_POST['genre_id']) ? (int) $_POST['genre_id'] : null;
            $year = $_SESSION['year'] = isset($_POST['year']) ? (int) $_POST['year'] : null;

            // валидация
            $title = $this->validateTitle($title);
            $year = $this->validateYear($year);

            // добавляем книгу
            $sqlBook = "INSERT INTO books (`title`, `genre_id`, `year`) VALUES ('$title', '$genre_id' , '$year')";
            $this->mysql->query($sqlBook);

            // проверяем ошибки и получаем последний id в таблице книги
            if (!empty($this->mysql->errno)) {
                $_SESSION['error'] = 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
            } else {
                $lastId = $this->mysql->insert_id;

                // заносим в связующую таблицу последний id книги и id каждого автора из формы
                foreach ($authors_id as $author_id) {
                    $sqlBookAuthor = "INSERT INTO book_author (`book_id`, `author_id`) VALUES ('$lastId', '$author_id')";
                    $this->mysql->query($sqlBookAuthor);
                }

                // проверяем на ошибку и сообщаем успешность
                if (!empty($this->mysql->errno)) {
                    $_SESSION['error'] = 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
                } else {
                    $_SESSION['message'] = "Запись [#$lastId] успешно добавлена в базу данных!";
                    unset($_SESSION['title']);
                    unset($_SESSION['authors_id']);
                    unset($_SESSION['genre_id']);
                    unset($_SESSION['year']);
                    header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                    die;
                }
            }
        }
    }

    public function updateBook()
    {
        if (isset($_POST['submit'])) {
            $id = $this->getIdFromUrl();

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
                $_SESSION['error'] = 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
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
                    $_SESSION['error'] = 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
                } else {
                    $_SESSION['message'] = "Запись [#$id] успешно изменена!";
                    header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
                    die;
                }
            }
        };
    }
    public function deleteBook()
    {
        if (isset($_POST['submit'])) {
            $id = $this->getIdFromUrl();

            // удаление книги и сообщение об успешности или ошибке
            $delSql = "DELETE FROM books WHERE `id`= '$id'";
            $this->mysql->query($delSql);
            if (!empty($this->mysql->errno)) {
                $_SESSION['error'] = 'ошибка' . ' ' . $this->mysql->errno . ': ' . $this->mysql->error;
            } else {
                $_SESSION['message'] = "Запись [#$id] успешно удалена!";
                header("Location: /books/index", true, 303);
                die;
            }
        }
    }

    public function getBooksByAuthor($authorId)
    {
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
