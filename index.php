<?php

// require_once 'app/bootstrap.php';

use App\core\Route;

require_once realpath("vendor/autoload.php");

define('ROOT', '/php-test/test_task_books/');

// класс для обработки маршрута в строке браузера
Route::start();
