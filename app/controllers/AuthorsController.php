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
        $this->view->render('authorsView.php', 'layoutView.php', null, $authors);
    }

    public function actionShow()
    {
        $author = $this->modelAuthors->getOne();
        $authorId = $this->modelAuthors->getIdFromUrl();
        $books = $this->modelBooks->getBooksByAuthor($authorId);
        $this->view->render('authorOneView.php', 'layoutView.php', $books, $author);
    }

    public function actionCreate()
    {
        $this->modelAuthors->addAuthor();
        $this->view->render('authorsCreateView.php', 'layoutView.php');
    }

    public function actionEdit()
    {
        $author = $this->modelAuthors->getOne();
        $this->modelAuthors->updateAuthor();
        $this->view->render('authorsEditView.php', 'layoutView.php', null, $author);
    }

    public function actionDelete()
    {
        $author = $this->modelAuthors->getOne();
        $this->modelAuthors->deleteAuthor();
        $this->view->render('authorDeleteView.php', 'layoutView.php', null, $author);
    }
}
