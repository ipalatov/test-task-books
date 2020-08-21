<?php
session_start();
use App\core\Route;

require_once realpath("vendor/autoload.php");

// класс для обработки маршрута в строке браузера
Route::start();
