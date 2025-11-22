-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 18 2025 г., 07:13
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `crumb_and_co`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int NOT NULL,
  `session_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`) VALUES
(5, 'Торты', 'cakes'),
(6, 'Десерты', 'desserts'),
(7, 'Пирожные', 'pastries'),
(8, 'Бенто-торты', 'bento-cakes');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'new',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_name`, `user_phone`, `user_email`, `product_name`, `product_id`, `price`, `comment`, `status`, `created_at`) VALUES
(2, 'qw', '12345678910', 'qwert@gmail.com', 'Бенто-торт \"Кис-Кис\"', 25, '1000.00', '', 'completed', '2025-11-17 12:54:40'),
(3, 'qw', '1111111111', 'qwert@gmail.com', 'Бенто-торт \"Кис-Кис\"', 25, '1000.00', '', 'new', '2025-11-17 13:10:11');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price_min` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price_min`, `image`, `category_id`) VALUES
(1, 'Кольцо с творогом', '', '90.00', '691acece19285.jpg', 7),
(2, 'Трубочка с белковым кремом', '', '80.00', '691acea7bcfbc.jpg', 7),
(3, 'Пирожное \"Картошка\"', 'Бисквитное тесто с подогревом, масляный основной крем на сахарном сиропе, коньяк, какао-порошок, сахарная пудра.', '150.00', '691ace75ea746.jpg', 7),
(4, 'Пирожное \"Шу\" шоколадное', 'Тоненькое заварное тесто, начинка из воздушного заварного крема с творожным сыром, сверху пирожное покрыто хрустящим кракелином.', '95.00', '691acef0c5130.jpg', 7),
(5, 'Десерт Татьяна', 'Шоколадный бисквит, сгущенное молоко со сливками, с добавлением грецкого ореха', '215.00', '691acdf7720ba.jpg', 6),
(6, 'Десерт \"Брауни\"', 'Шоколадный бисквит, пропитанный сгущенным молоком и сметаной, оформлен трюфельной крошкой и темным шоколадом.', '250.00', '691acdd665361.jpg', 6),
(7, 'Три шоколада', 'Муссовый десерт на основе темного, молочного и белого шоколада.', '270.00', '691acd24c6686.jpg', 6),
(8, 'Тирамису', 'Нежный десерт на основе сливочного крема и печенья Савоярди, пропитанного кофейным сиропом', '270.00', '691acd82c1853.jpg', 6),
(24, 'Бенто-торт \"Мама, я люблю тебя\"', '', '1000.00', '691ade4ac0004.jpg', 8),
(25, 'Бенто-торт \"Кис-Кис\"', '', '1000.00', '691adea0c172c.jpg', 8),
(26, 'Бенто-торт \"Happy birthday\"', '', '1000.00', '691adebeb246e.jpg', 8),
(28, 'Торт \"Сникерс\"', 'Шоколадный бисквит, пропитанный сахарным сиропом, начинка из арахиса с вареным сгущенным молоком, арахисовый крем.', '1350.00', '691adf7a64f19.jpg', 5),
(29, 'Торт «Капучино»', 'Торт состоит из бисквита на белом шоколаде, прослоенного сырным кремом с добавлением соленой карамели.', '1145.00', '691adfa4d0b77.jpeg', 5),
(30, 'Торт \"Малин Руж\"', 'Бисквит «красный бархат», пропитанный малиновым вареньем нашего собственного производства, прослоен сметанным кремом', '885.00', '691adfc65bbfe.jpg', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `user_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `review_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_approved` tinyint(1) DEFAULT '0'
) ;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `user_name`, `email`, `review_text`, `rating`, `photo`, `created_at`, `is_approved`) VALUES
(1, 'Мария Иванова', 'maria@gmail.com', 'Заказывала торт на день рождения дочери. Очень вкусно и красиво! Все гости были в восторге.', 5, 'uploads/reviews/review_6916c546395b47.34311481.jpg', '2025-11-14 08:59:34', 1),
(8, 'Алексей Петров', 'alex@gmail.com', 'Печенье с предсказаниями было хитом на нашей вечеринке. Все оценили оригинальную идею и вкус.', 5, 'uploads/reviews/review_691ad29d99c698.99441721.jpg', '2025-11-17 10:45:33', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `created_at`, `phone`, `city`) VALUES
(1, 'Тест', 'test@example.com', '$2y$10$abcdefghijklmnopqrstuv', '2025-11-14 08:10:31', NULL, NULL),
(4, 'qw', 'qwert@gmail.com', '$2y$10$sZMMdTILa/37JhDZKMktR.bo9LRNhjNd2g8R/LnfeQrDENyGbXzdC', '2025-11-15 03:30:04', '', '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
