<?php

namespace App\controllers;

use App\core\Controller;
use App\core\Pagination;
use App\models\Authors;
use App\models\Books;
use App\models\Genres;

class booksController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modelBooks = Books::getBookInstance();
        $this->modelAuthors = Authors::getAuthorInstance();
        $this->modelGenres = Genres::getGenreInstance();
    }
    public function actionIndex()
    {
        $total = $this->modelBooks->getTotal();
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $pageSize = 10;

        $pagination = new Pagination($page, $pageSize, $total);
        $offset = $pagination->getOffset();

        $books = $this->modelBooks->getIndex($offset, $pageSize);

        $authors = $this->modelAuthors->getIndex();

        $genres = $this->modelGenres->getIndex();

        $this->view->render('booksView.php', 'layoutView.php', $books, $authors, $genres, $pagination);
    }

    public function actionCreate()
    {
        $genres = $this->modelGenres->getIndex();

        $authors = $this->modelAuthors->getIndex();

        $this->modelBooks->addBook();

        $this->view->render('booksCreateView.php', 'layoutView.php', null, $authors, $genres);
    }

    public function actionShow()
    {
        $book = $this->modelBooks->getOne();
        $this->view->render('bookOneView.php', 'layoutView.php', $book);
    }

    public function actionEdit()
    {
        $book = $this->modelBooks->getOne();

        $genres = $this->modelGenres->getIndex();

        $authors = $this->modelAuthors->getIndex();

        $this->modelBooks->updateBook();

        $this->view->render('booksEditView.php', 'layoutView.php', $book, $authors, $genres);
    }

    public function actionDelete()
    {
        $book = $this->modelBooks->getOne();

        $this->modelBooks->deleteBook();

        $this->view->render('bookDeleteView.php', 'layoutView.php', $book);
    }
}
