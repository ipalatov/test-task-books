<?php

namespace App\core;

use App\models\Book;
use App\models\Author;
use App\models\Genre;

class Controller
{
    /**
     * Экземпляр класса App\models\Book
     * 
     * @var Book
     */
    public $modelBooks;

    /**
     * Экземпляр класса App\models\Author 
     * 
     * @var Author 
     */
    public $modelAuthors;

    /**
     * Экземпляр класса App\models\Genre 
     * 
     * @var Genre
     */
    public $modelGenres;

    /**
     * Экземпляр класса App\core\View
     * 
     * @var View
     */
    public $view;

    public function __construct()
    {
        $this->view = new View();
    }
}
