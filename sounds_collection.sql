-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Янв 12 2024 г., 17:02
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `sounds_collection`
--

-- --------------------------------------------------------

--
-- Структура таблицы `sounds`
--

CREATE TABLE `sounds` (
  `id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `sounds`
--

INSERT INTO `sounds` (`id`, `category`, `title`, `file_path`, `user_id`, `created_at`, `description`) VALUES
(1, 'Fire', 'Campfire', 'sounds/fire/campfire.mp3', 1, '2024-01-09 11:27:44', NULL),
(2, 'City', 'Traffic', 'sounds/city/traffic.mp3', 2, '2024-01-09 11:27:44', NULL),
(23, 'fire', 'Fire in the Hole!!!', 'sounds/fire/65a01ac08c768_sound-of-a-fantastic-warm-fireplace-141728.mp3', 7, '2024-01-11 16:43:44', 'Hello World'),
(24, 'applause', 'fsdsgfdgsfg', 'sounds/applause/65a01bf03ed61_sound-of-a-fantastic-warm-fireplace-141728.mp3', 7, '2024-01-11 16:48:48', 'dfsgdfgdf ggdfg f d'),
(25, 'city', 'adsfsfd', 'sounds/city/65a01c37162b9_sound-of-a-fantastic-warm-fireplace-141728.mp3', 7, '2024-01-11 16:49:59', 'asdf asdf ds f'),
(26, 'applause', 'gfsdfdgdf', 'sounds/applause/65a01c4574740_sound-of-a-fantastic-warm-fireplace-141728.mp3', 7, '2024-01-11 16:50:13', 'hello'),
(27, 'city', 'City', 'sounds/city/65a14ac300a08_sound-of-a-fantastic-warm-fireplace-141728.mp3', 4, '2024-01-12 14:20:51', 'test');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `is_admin`) VALUES
(1, 'user1', 'hashed_password1', '2024-01-09 11:27:44', 0),
(2, 'user2', 'hashed_password2', '2024-01-09 11:27:44', 0),
(3, 'user3', 'hashed_password3', '2024-01-09 11:27:44', 0),
(4, 'admin', '$2y$10$XbGhxOmPmttUzliqw3chtevXTUVYSvjYPLvH1.dy.c4IZ8JTIm6Xy', '2024-01-09 11:53:33', 1),
(6, 'test', '$2y$10$dCchWKPWEaoQ5R4BsZZJ0elBwx1dLXsRGrxCrxS18yl50/zZibcdm', '2024-01-11 16:09:22', 1),
(7, 'user', '$2y$10$otdv50.GxjEcjpC65dIH4OJU8HAJVk1gA7ys91LP8ExH8O8yJ94Dy', '2024-01-11 16:26:34', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `sounds`
--
ALTER TABLE `sounds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `sounds`
--
ALTER TABLE `sounds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `sounds`
--
ALTER TABLE `sounds`
  ADD CONSTRAINT `sounds_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
