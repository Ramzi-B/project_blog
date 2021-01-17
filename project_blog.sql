-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : Dim 17 jan. 2021 à 19:04
-- Version du serveur :  10.5.8-MariaDB
-- Version de PHP : 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `project_blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`, `email`, `created`, `updated`) VALUES
(1, 'admin', '$2y$10$ouL/hR4vwFKjsqdXAF1QhOUNIQtVh7s4bWwo7wB.LgncVXO3dxI3G', 'admin@gmx.com', '2019-10-27 08:48:47', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) UNSIGNED NOT NULL,
  `authorName` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authorEmail` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `authors`
--

INSERT INTO `authors` (`id`, `authorName`, `authorEmail`) VALUES
(1, 'Rams', 'bauwden.ramsey@gmail.com'),
(10, 'alice', 'alice@gmx.com'),
(11, 'hack', 'hack@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `categoryName` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `categoryName`) VALUES
(1, 'linux'),
(2, 'php'),
(3, 'javascript'),
(4, 'css'),
(5, 'html'),
(6, 'Développement'),
(7, 'programmation'),
(9, 'Divers');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approuved` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `name`, `content`, `created`, `updated`, `post_id`, `email`, `ip`, `url`, `agent`, `website`, `approuved`) VALUES
(112, 'alice', 'un autre test ', '2020-12-13 22:06:16', '2021-01-03 16:05:20', 98, 'alice@gmx.com', NULL, NULL, NULL, '', NULL),
(116, 'rams', 'encore un tes', '2020-12-18 23:55:42', '2020-12-25 01:17:38', 92, 'hack@gmx.com', NULL, NULL, NULL, 'http://monblog.fr', NULL),
(117, 'rams', 'un autre test', '2020-12-18 23:56:32', '0000-00-00 00:00:00', 92, 'hack@gmx.com', NULL, NULL, NULL, 'http://monblog.fr', NULL),
(119, 'alice', 'Magnif', '2020-12-19 09:47:38', '2020-12-25 02:08:35', 96, 'alice@gmx.com', NULL, NULL, NULL, '', NULL),
(126, 'rams', 'encore des test ', '2020-12-25 02:07:06', NULL, 96, 'test@test.fr', NULL, NULL, NULL, '', NULL),
(128, 'hack', 'encore un autre c\'est cool', '2020-12-25 07:14:18', '2020-12-26 10:51:23', 106, 'hack@gmail.com', NULL, NULL, NULL, '', NULL),
(129, 'rams', 'test new comment', '2020-12-25 07:54:10', NULL, 106, 'rams@gmx.com', NULL, NULL, NULL, '', NULL),
(130, 'admin', 'je test', '2020-12-25 13:50:40', '2020-12-26 10:47:40', 106, 'admin@gmail.com', NULL, NULL, NULL, 'http://monblog.fr', NULL),
(131, 'hackk', 'salut encore un test', '2021-01-13 13:26:07', NULL, 106, 'hack@gmail.com', NULL, NULL, NULL, '', NULL),
(132, 'hack', 'c\'est un petit peu galère d\'arrêter et de reprendre ! Non ?', '2021-01-16 17:47:39', '2021-01-17 06:08:16', 110, 'hack@gmail.com', NULL, NULL, NULL, '', NULL),
(134, 'alice', 'test if last comment works', '2021-01-17 16:34:38', NULL, 97, 'alice@gmx.com', NULL, NULL, NULL, '', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `published` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `author_id`, `category_id`, `created`, `updated`, `published`) VALUES
(90, 'Les balises', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 11, 5, '2020-04-26 05:59:17', '2020-12-03 16:51:15', 0),
(91, 'another test', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 11, 6, '2020-04-26 21:47:48', '2020-12-03 16:48:57', 1),
(92, 'test', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 10, 4, '2020-04-26 22:09:33', '2020-12-03 16:48:50', 0),
(93, 'test post', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 11, 7, '2020-04-26 22:09:50', '2020-12-03 16:48:31', 0),
(94, 'test new post', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 10, 5, '2020-04-26 22:10:08', '2020-12-03 16:46:20', 0),
(95, 'test new posts', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 11, 6, '2020-11-06 22:53:21', '2020-12-03 16:45:58', 1),
(96, 'POO title nice', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 1, 1, '2020-12-01 14:01:32', '2021-01-17 16:39:07', 1),
(97, 'Installer Arch', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 10, 1, '2020-12-01 14:02:44', '2020-12-03 16:48:09', 0),
(98, 'MVC', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 1, 1, '2020-12-01 14:05:06', '2020-12-25 06:31:47', 1),
(106, 'test new post', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 11, 3, '2020-12-15 23:36:02', '2020-12-25 06:23:01', 0),
(110, 'another test', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 1, 1, '2020-12-25 13:33:43', '2021-01-13 13:27:29', 0),
(112, 'A new one', 'consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa \r\nsapiente.Lorem ipsum dolor siLorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt\r\noptio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam deleniti vitae recusandae,\r\ntenetur doloribus blanditiis a vero culpa sapiente.\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minus saepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nLorem ipsum dolor sit amet consectetur adipisicing elit.\r\nNesciunt optio ad nihil inventore minussaepe ipsum tempora aperiam debitis veniam\r\ndeleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.\r\n\r\nt amet consectetur adipisicing elit. Nesciunt optio ad nihil inventore minus saepe ipsum tempora\r\naperiam debitis veniam deleniti vitae recusandae, tenetur doloribus blanditiis a vero culpa sapiente.', 10, 3, '2021-01-17 16:36:36', '2021-01-17 16:37:03', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'admin'),
(2, 'moderateur'),
(3, 'visiteur');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_authors` (`author_id`),
  ADD KEY `FK_categories` (`category_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `FK_authors` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `FK_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
