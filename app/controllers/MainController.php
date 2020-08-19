<?php

namespace App\controllers;

use App\core\Controller;
use App\core\View;

class MainController extends Controller
{

    public function __construct()
    {
        $this->view = new View();
    }

    public function actionIndex()
    {
        $this->view->render('main/indexView.php', 'layoutView.php');
    }
}
