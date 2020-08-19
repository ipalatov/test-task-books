<?php

namespace App\core;

class View
{
    public function render($contentView, $layotView, $books = null, $authors = null, $genres = null, $pagination = null)
    {
        $ini = parse_ini_file('./app/core/config.ini');

        include 'app/views/layouts/' . $layotView;
    }
}
