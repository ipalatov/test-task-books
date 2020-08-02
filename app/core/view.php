<?php

namespace App\core;

class View
{
    public function render($contentView, $layotView, $books = null, $authors = null, $genres = null, $pagination = null)
    {
        include 'app/views/layouts/' . $layotView;
    }
}
