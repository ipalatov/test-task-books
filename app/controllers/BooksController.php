<?php

namespace App\controllers;

use App\core\Controller;
use App\core\Pagination;
use App\models\Author;
use App\models\Book;
use App\models\Genre;

class BooksController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modelBooks = Book::getBookInstance();
        $this->modelAuthors = Author::getAuthorInstance();
        $this->modelGenres = Genre::getGenreInstance();
    }
    public function actionIndex()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $pageSize = 10;
        $total = $this->modelBooks->getTotal();
        $pagination = new Pagination($page, $pageSize, $total);
        $offset = $pagination->getOffset();
        $books = $this->modelBooks->getIndex($offset, $pageSize);

        $authors = $this->modelAuthors->getIndex();

        $genres = $this->modelGenres->getIndex();

        $this->view->render('books/indexView.php', 'layoutView.php', compact('books', 'authors', 'genres', 'pagination'));
    }

    public function actionCreate()
    {
        $genres = $this->modelGenres->getIndex();

        $authors = $this->modelAuthors->getIndex();

        $this->modelBooks->addBook();

        $this->view->render('books/createView.php', 'layoutView.php', compact('authors', 'genres'));
    }

    public function actionShow()
    {
        $book = $this->modelBooks->getOne();
        $this->view->render('books/showView.php', 'layoutView.php', compact('book'));
    }

    public function actionEdit()
    {
        $book = $this->modelBooks->getOne();

        $genres = $this->modelGenres->getIndex();

        $authors = $this->modelAuthors->getIndex();

        $this->modelBooks->updateBook($book['id']);

        $this->view->render('books/editView.php', 'layoutView.php', compact('book', 'genres', 'authors'));
    }

    public function actionDelete()
    {
        $book = $this->modelBooks->getOne();

        $this->modelBooks->deleteBook($book['id']);

        $this->view->render('books/deleteView.php', 'layoutView.php', compact('book'));
    }
}
