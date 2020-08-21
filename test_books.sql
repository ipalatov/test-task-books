-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `test_books`;
CREATE DATABASE `test_books` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `test_books`;

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `authors`;
CREATE TABLE `authors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `authors` (`id`, `name`) VALUES
(6,	'Акунин Б.'),
(5,	'Булгаков М.А.'),
(3,	'Ильф И.А.'),
(7,	'Пелевин В.О.'),
(4,	'Петров Е.П.'),
(1,	'Пушкин А.С. '),
(2,	'Толстой Л.Н.');

DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `genre_id` bigint(20) unsigned NOT NULL,
  `year` bigint(4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `genre_id` (`genre_id`),
  CONSTRAINT `books_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `books` (`id`, `title`, `genre_id`, `year`) VALUES
(1,	'Золотая рыбка',	3,	1835),
(2,	'Руслан и Людмила',	2,	1820),
(3,	'Война и мир',	1,	1867),
(4,	'Анна Каренина',	1,	1878),
(5,	'Двенадцать стульев',	1,	1928),
(6,	'Капитанская дочка',	1,	1836),
(7,	'Исповедь',	4,	1884),
(8,	'Сказка о попе и о работнике его Балде',	3,	1840),
(9,	'Сказка о царе Салтане',	3,	1832),
(10,	'Сказка о мёртвой царевне и о семи богатырях',	3,	1834),
(11,	'Медный всадник',	2,	1837),
(12,	'Кавказский пленник',	2,	1822),
(13,	'Цыганы',	2,	1827),
(14,	'Евгений Онегин',	1,	1833),
(15,	'Дубровский',	1,	1841),
(16,	'Белая гвардия',	1,	1925),
(17,	'Иван Васильевич',	7,	1965),
(18,	'Мастер и Маргарита',	1,	1966),
(19,	'Собачье сердце',	6,	1987),
(20,	'Азазель',	8,	1998),
(21,	'Турецкий гамбит',	8,	1998),
(22,	'Статский советник',	1,	1999),
(23,	'Чапаев и Пустота',	1,	1997),
(24,	'Generation «П»',	1,	1999);

DROP TABLE IF EXISTS `book_author`;
CREATE TABLE `book_author` (
  `book_id` bigint(20) unsigned NOT NULL,
  `author_id` bigint(20) unsigned NOT NULL,
  KEY `book_id` (`book_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `book_author_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `book_author_ibfk_3` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `book_author` (`book_id`, `author_id`) VALUES
(1,	1),
(2,	1),
(3,	2),
(4,	2),
(5,	3),
(5,	4),
(6,	1),
(7,	2),
(8,	1),
(9,	1),
(10,	1),
(11,	1),
(12,	1),
(13,	1),
(14,	1),
(15,	1),
(16,	5),
(17,	5),
(18,	5),
(19,	5),
(20,	6),
(21,	6),
(22,	6),
(23,	7),
(24,	7);

DROP TABLE IF EXISTS `genres`;
CREATE TABLE `genres` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `genres` (`id`, `name`) VALUES
(1,	'Роман'),
(2,	'Поэма'),
(3,	'Сказка'),
(4,	'Автобиография'),
(5,	'Рассказ'),
(6,	'Повесть'),
(7,	'Пьеса'),
(8,	'Детектив');

-- 2020-08-21 07:02:31
