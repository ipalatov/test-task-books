<?php

namespace App\core;

class Controller
{
    public $modelBooks;
    public $modelAuthors;
    public $modelGenres;
    public $view;

    public function __construct()
    {
        $this->view = new View();
    }

}
