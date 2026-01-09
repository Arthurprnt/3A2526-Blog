-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 09, 2026 at 10:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Articles`
--

CREATE TABLE `Articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `utilisateur_id` int(10) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `image_une` varchar(255) DEFAULT NULL,
  `statut` enum('Brouillon','Publié','Archivé') DEFAULT 'Brouillon',
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_mise_a_jour` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Articles`
--

INSERT INTO `Articles` (`id`, `utilisateur_id`, `titre`, `slug`, `contenu`, `image_une`, `statut`, `date_creation`, `date_mise_a_jour`) VALUES
(1, 1, 'Top 5 des Traces VTT Enduro en Rhône-Alpes', 'top-5-traces-vtt-enduro-rhone-alpes', '#\r\nIntroduction\r\nDécouvrez les descentes les plus mythiques pour les amateurs d\'**Enduro** en France. Freins\r\npuissants et protections obligatoires !\r\n## La piste de l\'Écureuil\r\nUne trace rapide avec de gros dénivelés négatifs. Idéale pour tester votre **suspension**.', NULL, 'Publié', '2025-11-19 14:38:12', '2025-11-19 14:38:12'),
(2, 2, 'Réglage de la suspension : le SAG parfait pour le XC', 'reglage-suspension-sag-xc', 'Le **SAG**\r\n(Sinking At Ground) est crucial en **XC** pour optimiser le rendement et le confort. Nous détaillons\r\nici le processus étape par étape. Un mauvais réglage impacte directement la performance.', NULL, 'Publié', '2025-11-19 14:38:12', '2025-11-19 14:38:12'),
(3, 3, 'Gérer l\'Hydratation sur une longue sortie VTT', 'gerer-hydratation-longue-sortie', 'Au-delà de\r\n3h, l\'eau seule ne suffit plus. Il faut intégrer des électrolytes et des glucides. Notre guide complet sur\r\nla **Nutrition** et l\'**Hydratation**.', NULL, 'Brouillon', '2025-11-19 14:38:12', '2025-11-19 14:38:12'),
(5, 13, 'Test d\'article', 'test', '<h1>ANNONCE</h1><p>Bah la <strong>par exemple</strong> c\'est un post créé via le site lui même <em>mdrr</em></p><p><img src=\"https://kaamelott-gifboard.fr/gifs/meme-un-debile-comprendrait.gif\" alt=\"meme\" style=\"width: 25%;\"></p>', NULL, NULL, '2025-11-21 20:26:27', '2026-01-09 22:02:08'),
(13, 13, 'Gérer l\'Hydratation sur une longue sortie VTT', 'grer-lhydratation-sur-une-longue-sortie-vtt', '<p>Au-delà de\r\n3h, l\'eau seule ne suffit plus. Il faut intégrer des électrolytes et des glucides. Notre guide complet sur\r\nla **Nutrition** et l\'**Hydratation**. Ijforjedgqjorgjiqsreg</p>', NULL, 'Publié', '2026-01-08 20:34:12', '2026-01-08 20:34:12');

-- --------------------------------------------------------

--
-- Table structure for table `Article_Tag`
--

CREATE TABLE `Article_Tag` (
  `article_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Article_Tag`
--

INSERT INTO `Article_Tag` (`article_id`, `tag_id`) VALUES
(1, 1),
(1, 2),
(1, 4),
(2, 3),
(2, 4),
(3, 5),
(3, 7);

-- --------------------------------------------------------

--
-- Table structure for table `Commentaires`
--

CREATE TABLE `Commentaires` (
  `id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `nom_auteur` varchar(100) NOT NULL,
  `email_auteur` varchar(100) DEFAULT NULL,
  `contenu` text NOT NULL,
  `statut` enum('En attente','Approuvé','Rejeté') DEFAULT 'En attente',
  `date_commentaire` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Commentaires`
--

INSERT INTO `Commentaires` (`id`, `article_id`, `nom_auteur`, `email_auteur`, `contenu`, `statut`, `date_commentaire`) VALUES
(1, 1, 'Nicolas Rider', 'nic@trail.fr', 'Super article, je connaissais pas la piste de l\'Écureuil ! J\'y vais ce\r\nweekend.', 'Approuvé', '2025-11-19 14:38:12'),
(3, 2, 'ProXC', 'pro@xc.com', 'J\'utilise le même SAG, c\'est le meilleur compromis !', 'Approuvé', '2025-11-19 14:38:12');

-- --------------------------------------------------------

--
-- Table structure for table `Permissions`
--

CREATE TABLE `Permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom_permission` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Permissions`
--

INSERT INTO `Permissions` (`id`, `nom_permission`) VALUES
(1, 'admin_access'),
(2, 'article_creer'),
(3, 'article_editer_tous'),
(4, 'article_publier'),
(5, 'article_supprimer'),
(6, 'commentaire_gerer'),
(8, 'tag_gerer'),
(7, 'utilisateur_gerer');

-- --------------------------------------------------------

--
-- Table structure for table `Roles`
--

CREATE TABLE `Roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom_role` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Roles`
--

INSERT INTO `Roles` (`id`, `nom_role`, `description`) VALUES
(1, 'Administrateur', 'Accès complet au tableau de bord et à la gestion des utilisateurs.'),
(2, 'Éditeur', 'Peut créer, modifier et publier ses propres articles et ceux des contributeurs.'),
(3, 'Contributeur', 'Peut créer et modifier ses propres articles (statut Brouillon uniquement).');

-- --------------------------------------------------------

--
-- Table structure for table `Role_Permission`
--

CREATE TABLE `Role_Permission` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Role_Permission`
--

INSERT INTO `Role_Permission` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 6),
(2, 8),
(3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Role_User`
--

CREATE TABLE `Role_User` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Role_User`
--

INSERT INTO `Role_User` (`role_id`, `user_id`) VALUES
(1, 13),
(2, 2),
(3, 2),
(3, 3),
(3, 13);

-- --------------------------------------------------------

--
-- Table structure for table `Tags`
--

CREATE TABLE `Tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom_tag` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Tags`
--

INSERT INTO `Tags` (`id`, `nom_tag`, `slug`) VALUES
(1, 'Traces GPS', 'traces-gps'),
(2, 'Enduro', 'enduro'),
(3, 'XC', 'xc'),
(4, 'Suspension', 'suspension'),
(5, 'Nutrition', 'nutrition'),
(6, 'Entraînement', 'entrainement'),
(7, 'Hydratation', 'hydratation');

-- --------------------------------------------------------

--
-- Table structure for table `Utilisateurs`
--

CREATE TABLE `Utilisateurs` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom_utilisateur` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` char(60) NOT NULL,
  `est_actif` tinyint(1) DEFAULT 1,
  `date_inscription` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Utilisateurs`
--

INSERT INTO `Utilisateurs` (`id`, `nom_utilisateur`, `email`, `mot_de_passe`, `est_actif`, `date_inscription`) VALUES
(1, 'AdminVTT', 'admin@vtt.com', '$2y$10$Q7iR7/h7Gq6yRzW2gP0pT.0.1oQ5t4T8W0y5fG8E7C8zM7/V2C9a', 1, '2025-11-19 14:38:12'),
(2, 'EditeurTrail', 'editeur@vtt.com', '$2y$10$Q7iR7/h7Gq6yRzW2gP0pT.0.1oQ5t4T8W0y5fG8E7C8zM7/V2C9a', 1, '2025-11-19 14:38:12'),
(3, 'ContributeurRando', 'contributeur@vtt.com', '$2y$10$Q7iR7/h7Gq6yRzW2gP0pT.0.1oQ5t4T8W0y5fG8E7C8zM7/V2C9a', 1, '2025-11-19 14:38:12'),
(13, 'Arthur', 'arthur.perronnet@gmail.com', '$2y$10$ub3BZAspgq7g7HSKfcNIb.lvubwIqhuwE3PzjTDvN6k0Ut5gV8joO', 1, '2025-11-19 16:47:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Articles`
--
ALTER TABLE `Articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Indexes for table `Article_Tag`
--
ALTER TABLE `Article_Tag`
  ADD PRIMARY KEY (`article_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `Commentaires`
--
ALTER TABLE `Commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indexes for table `Permissions`
--
ALTER TABLE `Permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom_permission` (`nom_permission`);

--
-- Indexes for table `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom_role` (`nom_role`);

--
-- Indexes for table `Role_Permission`
--
ALTER TABLE `Role_Permission`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `Role_User`
--
ALTER TABLE `Role_User`
  ADD PRIMARY KEY (`role_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Tags`
--
ALTER TABLE `Tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom_tag` (`nom_tag`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom_utilisateur` (`nom_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Articles`
--
ALTER TABLE `Articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `Commentaires`
--
ALTER TABLE `Commentaires`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `Permissions`
--
ALTER TABLE `Permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Roles`
--
ALTER TABLE `Roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Tags`
--
ALTER TABLE `Tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Articles`
--
ALTER TABLE `Articles`
  ADD CONSTRAINT `Articles_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `Utilisateurs` (`id`);

--
-- Constraints for table `Article_Tag`
--
ALTER TABLE `Article_Tag`
  ADD CONSTRAINT `Article_Tag_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `Articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Article_Tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `Tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Commentaires`
--
ALTER TABLE `Commentaires`
  ADD CONSTRAINT `Commentaires_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `Articles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Role_Permission`
--
ALTER TABLE `Role_Permission`
  ADD CONSTRAINT `Role_Permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `Roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Role_Permission_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `Permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Role_User`
--
ALTER TABLE `Role_User`
  ADD CONSTRAINT `Role_User_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `Roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Role_User_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Utilisateurs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
