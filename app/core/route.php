<?php

namespace App\core;


class  Route
{
    static function start()
    {
        $controllerName = 'main';
        $actionName = 'index';

        // Разбиваем URL на части в массив
        $routeParts = explode('/', $_SERVER['REQUEST_URI']);

        $c = count($routeParts) - 2; // второй элемент с конца массива
        $a = count($routeParts) - 1; // первый элемент с конца массива

        //Получаем имя контроллера
        if (!empty($routeParts[$c])) {
            $controllerName = $routeParts[$c];
        }
        //Получаем имя действия (до знака "?")
        if (!empty($routeParts[$a])) {
            $actionName = strstr($routeParts[$a], '?', TRUE) ? strstr($routeParts[$a], '?', TRUE) : $routeParts[$a];
        }

        $controllerName = 'App\\controllers\\' . $controllerName . 'Controller';
        $actionName = 'action' . ucfirst($actionName);

        // исключение на случай отсуствия класса
        if (!class_exists($controllerName)) {
            throw new \ErrorException('Controller does not exist');
        }

        $objController = new $controllerName();

        // исключение на случай отсуствия метода класса
        if (!method_exists($objController, $actionName)) {
            throw new \ErrorException('Action does not exist');
        }

        $objController->$actionName();
    }
}
