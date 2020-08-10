
# О проекте

Тестовое задание на реализацию концепции MVC на чистом PHP (без фреймворков).

Текст задачи в файле test_task_books.txt в корневой директории репозитория.

## Примечания:
1) Маршрутизация прописана в app\core\route.php  по принципу /controller/action
2) В файле app/core/config.ini конфигурация проекта - доступ к базе данных, корневой каталог сайта и т.д..
3) Подключен автозагрузчик классов psr-4.
4) Во избежании создания более одного экземпляра одного класса использовался паттерн Singleton.
5) Во фронтенде только HTML. CSS стили и JS скрипты в проекте отсуствуют, чтобы не загромождать код, т.к. главная задача показать реализацию MVC.
6) Весь функционал, заявленный в задаче - выполнен.
7) По мере необходимости код прокомментирован.
8) Я новичок в PHP, поэтому возможно не знаю очевидных для опытных разработчиков моментов. Буду исправляться по мере получения опыта.
9) База данных (MySQL) состоит из 4-х таблиц:


I) books
поле	    Тип
id	    bigint(20) unsigned Автоматическое приращение
title	    varchar(250)
genre_id    bigint(20) unsigned
year	    bigint(4) unsigned

Индексы
PRIMARY	    id
UNIQUE	    title
INDEX	    genre_id

Внешние ключи
Источник	Цель	      При стирании	При обновлении
genre_id	genres(id)    CASCADE	        CASCADE

II) authors
поле	Тип
id	bigint(20) unsigned Автоматическое приращение
name	varchar(100)

Индексы
PRIMARY	id
UNIQUE	name

III) genres
поле	Тип
id	bigint(20) unsigned Автоматическое приращение
name	varchar(30)

Индексы
PRIMARY	id

IV) book_author (связующая)
поле	       Тип
book_id	       bigint(20) unsigned
author_id      bigint(20) unsigned

Индексы
INDEX	book_id
INDEX	author_id

Внешние ключи
Источник	Цель	        При стирании	При обновлении
book_id	        books(id)	CASCADE	        CASCADE
author_id	authors(id)	CASCADE	        CASCADE
