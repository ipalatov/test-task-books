-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `authors`;
CREATE TABLE `authors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `authors` (`id`, `name`) VALUES
(15,	'Ivan'),
(3,	'Ильф И.А.'),
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
(30,	'Исповедь',	4,	1884),
(31,	'Севастопольские рассказы',	5,	1857),
(39,	'Исповедь2',	2,	1878);

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
(31,	2),
(30,	2),
(6,	1),
(39,	3);

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
(5,	'Рассказ');

-- 2020-08-03 07:34:55
