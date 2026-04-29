-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 29 2026 г., 02:17
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `aquarium_shop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `session_id`, `product_id`, `quantity`, `added_at`) VALUES
(2, NULL, 'ba7l3hqa2nea8lna3qgg65e3re', 1, 1, '2026-04-06 21:29:30'),
(31, 4, '', 3, 1, '2026-04-07 12:54:19'),
(36, NULL, 'lh4v6f01bieimfr6sopia6mtk3', 5, 1, '2026-04-14 21:55:09'),
(39, 2, '', 1, 2, '2026-04-15 14:38:08'),
(40, 2, '', 1, 1, '2026-04-15 14:39:21'),
(41, 3, '', 1, 1, '2026-04-19 18:58:43');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Аквариумы'),
(2, 'Книги и аксессуары'),
(3, 'Корм и добавки'),
(4, 'Оборудование'),
(5, 'Рыбки и растения'),
(6, 'Украшения');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('new','processing','in_transit','delivered','cancelled','paid','shipped','completed') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `session_id`, `total`, `status`, `created_at`) VALUES
(1, 1, '', 4500.00, 'new', '2026-04-27 20:52:35'),
(2, 5, '', 50000.00, 'new', '0000-00-00 00:00:00'),
(3, 5, 'v8cv8ouvqisqn88t3fjinhkfns', 1440.00, 'processing', '2026-04-28 17:47:25'),
(4, 5, 'v8cv8ouvqisqn88t3fjinhkfns', 990.00, 'new', '2026-04-28 18:52:25'),
(5, 5, 'v8cv8ouvqisqn88t3fjinhkfns', 2070.00, 'cancelled', '2026-04-28 19:17:56');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 3, 2, 1, 990.00),
(2, 3, 3, 1, 450.00),
(3, 4, 2, 1, 990.00),
(4, 5, 4, 3, 690.00);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_sale` tinyint(1) DEFAULT 0,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'product-placeholder.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `description`, `price`, `stock`, `is_sale`, `sale_price`, `image`, `created_at`) VALUES
(1, 'Аквариум 60L', 'Аквариумы', 'Стеклянный аквариум 60 литров с крышкой', 4500.00, 10, 0, NULL, 'aquarium1.jpg', '2026-04-06 20:05:47'),
(2, 'Фильтр внутренний', 'Оборудование', 'Мощный фильтр для аквариумов до 100л', 1200.00, 25, 1, 990.00, 'philter.webp', '2026-04-06 20:05:47'),
(3, 'Корм TetraMin', 'Корм и добавки', 'Сбалансированный корм для всех видов рыб', 450.00, 50, 0, NULL, 'product-placeholder.png', '2026-04-06 20:05:47'),
(4, 'Декорация \"Корабль\"', 'Украшения', 'Керамический корабль, безопасный для рыб', 890.00, 7, 1, 690.00, 'product-placeholder.png', '2026-04-06 20:05:47'),
(5, 'Скалярия', 'Рыбки и растения', 'Живая рыбка, пара', 350.00, 12, 0, NULL, 'product-placeholder.png', '2026-04-06 20:05:47'),
(6, 'Книга \"Аквариумистика\"', 'Книги и аксессуары', 'Полное руководство', 1200.00, 5, 0, NULL, 'product-placeholder.png', '2026-04-06 20:05:47'),
(7, 'Корм очень питательный', 'Корм и добавки', 'Очень сильно питательный корм', 200.00, 1, 1, 150.00, 'product-placeholder.png', '2026-04-28 20:48:55');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default-avatar.png',
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `avatar`, `role`, `created_at`) VALUES
(1, 'Сергей', 'nasndnasdn@mail.ru', '123', 'cart.png', 'user', '2026-04-05 21:00:00'),
(2, 'Иван Машика', 'user@mail.ru', '$2y$10$ouIFBdkjf7W6i4tOARJmfeRtrpW470sabpNZpe/nH4RcNmnF1R4V6', 'default-avatar.png', 'user', '2026-04-06 20:20:03'),
(3, 'Павел', 'pavel@mail.ru', '$2y$10$UMqQnMulqSk4c/WpC32Ih.TvjYjYmrQwD6Ykzxo/Hswb7OL06qlEW', 'default-avatar.png', 'user', '2026-04-07 12:15:39'),
(4, 'Кошка', 'myaki@kotik.ru', '$2y$10$qc4cQiE/TpEEVB3/Ln3ja.Cs3juVfVrJ6bV/ZQ3UhcxzjA5S0duGS', 'default-avatar.png', 'user', '2026-04-07 12:40:43'),
(5, 'Сергей', 'sergey@mail.ru', '$2y$10$vJgn3QrLfeN5tRcfldDgA.tkeRtNb0peLI4CVI.Bxu6SXCwLf7CBO', 'default-avatar.png', 'user', '2026-04-27 20:54:34'),
(6, 'NIGGA', 'mash@mail.ru', '$2y$10$8rdpNuZoDKmhc7XfvtUDJ.QIn.8MmuVX6MurFkYtyaQe6s1uOqrqy', 'default-avatar.png', 'user', '2026-04-28 17:22:35'),
(9, 'Admin', 'admin@mail.ru', '$2y$10$D/surETJNseBj4dghe/10erzvXJR8VoGEcElrZNfjC39iiuSTczIS', 'default-avatar.png', 'admin', '2026-04-28 20:21:29');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
