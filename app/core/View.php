<?php

namespace App\core;

class View
{
    public function render($contentView, $layotView, $dataArray = null)
    {
        $ini = parse_ini_file('./app/core/config.ini');

        include 'app/views/layouts/' . $layotView;
    }
}
