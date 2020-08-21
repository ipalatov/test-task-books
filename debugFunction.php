<?php

function d($array)
{
    echo '<pre>';
    var_dump($array);
    echo '</pre>';
}

function dd($array)
{
    echo '<pre>';
    var_dump($array);
    echo '</pre>';
    die;
}
