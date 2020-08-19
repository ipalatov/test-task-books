<?php

namespace App\controllers;

use App\core\Controller;
use App\models\Author;
use App\models\Book;

class AuthorsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->modelAuthors = Author::getAuthorInstance();
        $this->modelBooks = Book::getBookInstance();
    }

    public function actionIndex()
    {

        $authors = $this->modelAuthors->getIndex();
        $this->view->render('authors/indexView.php', 'layoutView.php', compact('authors'));
    }

    public function actionShow()
    {
        $author = $this->modelAuthors->getOne();
        $authorId = $this->modelAuthors->getIdFromUrl();
        $books = $this->modelBooks->getBooksByAuthor($authorId);
        $this->view->render('authors/showView.php', 'layoutView.php', compact('author', 'authorId', 'books'));
    }

    public function actionCreate()
    {
        $this->modelAuthors->addAuthor();
        $this->view->render('authors/createView.php', 'layoutView.php');
    }

    public function actionEdit()
    {
        $author = $this->modelAuthors->getOne();
        $this->modelAuthors->updateAuthor();
        $this->view->render('authors/editView.php', 'layoutView.php', compact('author'));
    }

    public function actionDelete()
    {
        $author = $this->modelAuthors->getOne();
        $this->modelAuthors->deleteAuthor();
        $this->view->render('authors/deleteView.php', 'layoutView.php', compact('author'));
    }
}
