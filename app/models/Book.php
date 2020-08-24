<?php

namespace App\models;

use App\core\Model;
use App\core\Validator;
use PDO;

class Book extends Model
{

    /**
     * Экземпляры класса
     * 
     * @var array 
     */
    private static $bookInstances = [];

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
     * @return Book
     */
    public static function getInstance(): Book
    {
        $cls = static::class;
        if (!isset(self::$bookInstances[$cls])) {
            self::$bookInstances[$cls] = new static;
        }
        return self::$bookInstances[$cls];
    }


    /**
     * Получает данные фильтра по автору, записывает в сессию и возвращает строку для применения в SQL запросе
     * 
     * @var array $_POST['authorFilter'] Массив, полученный из POST запроса
     * @var string $filterAuthors Строка, полученная из массива для использования в sql-операторе IN 
     * @return string Строка на основе данных фильтра по автору, которая будет использоваться в итоговом SQL запросе
     */
    static function getQueryAuthors()
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

    /**
     * Получает данные фильтра по жанру, записывает в сессию и возвращает строку для применения в SQL запросе
     * 
     * @var array $_POST['genreFilter'] Массив, полученный из POST запроса
     * @var string $filterGenres Строка, полученная из массива для использования в sql-операторе IN 
     * @return string Строка на основе данных фильтра по жанру, которая будет использоваться в итоговом SQL запросе
     */
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

    /**
     *  Получает данные фильтра по годам, записывает в сессию и возвращает строку для применения в SQL запросе
     * 
     * @var int ($_POST['startYearFilter']) Начальный год, полученный из POST запроса
     * @var int ($_POST['endYearFilter'])Конечный год, полученный из POST запроса
     * @return string Строка на основе данных фильтра по годам, которая будет использоваться в итоговом SQL запросе
     */
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

    /**
     * Сбрасывает все фильтры, записанные в сессию
     * 
     * @return void
     */
    public function resetFilters()
    {
        if (isset($_POST['reset_filter'])) {
            unset($_SESSION['authorFilter']);
            unset($_SESSION['genreFilter']);
            unset($_SESSION['startYearFilter']);
            unset($_SESSION['endYearFilter']);
            unset($_POST['reset_filter']);
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
    }

    /**
     * Получает общее количество записей по условиям фильтра
     * 
     * @return int
     */
    public function getTotal()
    {
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

        $errorInfo = $pdoStat->errorInfo();
        if ($errorInfo[1]) {
            $_SESSION['error'] = 'ошибка: код ' . ' ' . $errorInfo[1] . ' - ' . $errorInfo[2];
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
        $total = $pdoStat->fetch(\PDO::FETCH_ASSOC);

        return $total['count'];
    }

    /**
     * Получает тип сортировки из GET запроса
     * 
     * @return string Строка c данными сортировки, которая будет использоваться в итоговом SQL запросе
     */
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
                case 'id_desc';
                    $sorting = 'book_id DESC';
                    break;
                default:
                    $sorting = 'book_id ASC';
                    break;
            }
        }
        return $sorting;
    }

    /**
     * Возвращает список всех книг на странице с учетом сортировки, фильтров и пагинации
     * 
     * @param int $offset Смещение для sql-оператора LIMIT 
     * @param int $limit Количество элементов на странице для sql-оператора LIMIT 
     * @return array
     */
    public function getIndex($offset, $limit)
    {
        $sorting = static::getSorting();

        $queryAuthors = static::getQueryAuthors();
        $queryGenres = static::getQueryGenres();
        $queryYears = static::getQueryYears();

        // запрос на список книг на странице (с конкатенацией по столбцу authors.name), с условиями по фильтру, сортировкой
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

    /**
     * Получает id книги из GET запроса и осуществляет проверку на существование такого в базе данных
     * 
     * @return int
     */
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

    /**
     * Возвращает из базы данных одну запись (книга) по id из GET запроса
     * 
     * @return array
     */
    public function getOne()
    {
        $id = $this->getIdFromUrl();

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

        $book = (array) $pdoStat->fetch(\PDO::FETCH_ASSOC);

        return $book;
    }

    /**
     * Проверяет строку на длину, а также уникальность в БД, с помощью абстрактного класса Validator
     * 
     * @param string $name
     * @param int|null $id
     * @return string Та же строка, если прошла все проверки
     */
    function validateTitle(string $title, int $id = null)
    {
        Validator::checkStringMin($title, 3);
        Validator::checkStringMax($title, 250);

        Validator::checkUniqField('books', 'title', $title, $id);

        return $title;
    }

    /**
     * Проверяет число, д.б. не больше текущего года, с помощью абстрактного класса Validator
     * 
     * @param int $year
     * @return int То же число, если прошло все проверки
     */
    function validateYear(int $year)
    {
        Validator::checkCurrentYear($year);

        return $year;
    }

    /**
     * Сбрасывает все данные формы , записанные в сессию
     * 
     * @return void
     */
    public function resetFormData()
    {
        unset($_SESSION['title']);
        unset($_SESSION['authors_id']);
        unset($_SESSION['genre_id']);
        unset($_SESSION['year']);
        unset($_POST['reset_filter']);
        header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
        die;
    }

    /**
     * Получает данные из формы, записывает в сессию, валидирует и добавляет в БД
     * 
     * @return void 
     */
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

            // сообщение об успешности и сброс данных формы , записанных в сессию
            $_SESSION['message'] = "Запись [#$lastId] успешно добавлена в базу данных!";
            $this->resetFormData();
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
        if (isset($_POST['reset'])) {
            $this->resetFormData();
        }
    }

    /**
     * Получает данные из формы, валидирует и обновляет в БД по $id книги
     * 
     * @param int $id id книги
     * @return void 
     */
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

    /**
     * Удаляет книгу из БД по $id
     * 
     * @param int $id id автора
     * @return void 
     */
    public function deleteBook($id)
    {
        if (isset($_POST['submit'])) {

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
        if (isset($_POST['noDelete'])) {
            header("Location: /books/show?id=$id", true, 303);
            die;
        }
    }

    /**
     * Получает список книг по id автора
     * 
     * @param int $authorId
     * @return array
     */
    public function getBooksByAuthor($authorId)
    {
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
