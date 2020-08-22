<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit81e1d009ed274a0f431095cc5f535e47
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'App\\controllers\\AuthorsController' => __DIR__ . '/../..' . '/app/controllers/AuthorsController.php',
        'App\\controllers\\BooksController' => __DIR__ . '/../..' . '/app/controllers/BooksController.php',
        'App\\controllers\\MainController' => __DIR__ . '/../..' . '/app/controllers/MainController.php',
        'App\\core\\Controller' => __DIR__ . '/../..' . '/app/core/Controller.php',
        'App\\core\\Model' => __DIR__ . '/../..' . '/app/core/Model.php',
        'App\\core\\Pagination' => __DIR__ . '/../..' . '/app/core/Pagination.php',
        'App\\core\\Route' => __DIR__ . '/../..' . '/app/core/Route.php',
        'App\\core\\Validator' => __DIR__ . '/../..' . '/app/core/Validator.php',
        'App\\core\\View' => __DIR__ . '/../..' . '/app/core/View.php',
        'App\\models\\Author' => __DIR__ . '/../..' . '/app/models/Author.php',
        'App\\models\\Book' => __DIR__ . '/../..' . '/app/models/Book.php',
        'App\\models\\Genre' => __DIR__ . '/../..' . '/app/models/Genre.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit81e1d009ed274a0f431095cc5f535e47::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit81e1d009ed274a0f431095cc5f535e47::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit81e1d009ed274a0f431095cc5f535e47::$classMap;

        }, null, ClassLoader::class);
    }
}
