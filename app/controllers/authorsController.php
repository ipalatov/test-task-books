<?php

namespace App\controllers;

use App\core\Controller;
use App\models\Authors;
use App\models\Books;

class authorsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->modelAuthors = Authors::getAuthorInstance();
        $this->modelBooks = Books::getBookInstance();
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
        $this->view->render('authorsCreateView.php', 'layoutView.php');
        $this->modelAuthors->addAuthor();
    }

    public function actionEdit()
    {
        $author = $this->modelAuthors->getOne();
        $this->view->render('authorsEditView.php', 'layoutView.php', null, $author);
        $this->modelAuthors->updateAuthor();
    }

    public function actionDelete()
    {
        $author = $this->modelAuthors->getOne();

        $this->view->render('authorDeleteView.php', 'layoutView.php', null, $author);
        $this->modelAuthors->deleteAuthor();
    }
}
