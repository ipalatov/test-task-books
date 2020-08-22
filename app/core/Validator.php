<?php

namespace App\core;

abstract class Validator
{
    static function checkStringMin(string $string, int $number)
    {
        if (mb_strlen($string) < $number) {
            $_SESSION['error'] = "Ошибка ввода - минимальная длина 
            поля - $number символа!";
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
    }

    static function checkStringMax(string $field, int $number)
    {
        if (mb_strlen($field) > $number) {
            $_SESSION['error'] = "Ошибка ввода - максимальная длина 
            поля - $number символов!";
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
    }

    static function checkUniqField(string $table, string $column, string $value, int $id = null)
    {
        $class = debug_backtrace()[1]['class'];
        $model = $class::getInstance();

        $idQuery = ($id) ? "AND `id`<> $id"  : "";
        $sqlUniq = "SELECT `id` FROM `$table` WHERE `$column` = :$column $idQuery";
        $params = [$column => $value];

        $pdoStat = $model->pdo->prepare($sqlUniq);
        $pdoStat->execute($params);;
        if ($pdoStat->rowCount() > 0) {
            $_SESSION['error'] = 'Ошибка ввода - уже есть такая запись';
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
    }

    static function checkCurrentYear(int $year)
    {
        if ($year > date('Y')) {
            $_SESSION['error'] = 'Ошибка ввода - год издания больше текущего';
            header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
            die;
        }
    }
}
