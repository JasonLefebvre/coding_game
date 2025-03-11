-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 11 mars 2025 à 16:25
-- Version du serveur : 10.11.6-MariaDB-0+deb12u1
-- Version de PHP : 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `coding_game`
--

-- --------------------------------------------------------

--
-- Structure de la table `atelier_ecriture`
--

CREATE TABLE `atelier_ecriture` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `atelier_ecriture`
--

INSERT INTO `atelier_ecriture` (`id`, `titre`, `description`, `date`, `heure_debut`, `heure_fin`) VALUES
(1, 'Atelier #1 - Noël', 'Écrire sur Noël peu importe qu’on aime ou pas cette période', '2024-12-15', '20:30:00', '19:00:00'),
(2, 'Atelier #2 - Merci 2024', 'Dire merci à 2024... ou pas !', '2024-12-29', '10:30:00', '12:00:00'),
(3, 'Atelier #3 - En avant 2025', 'Se mettre en action pour 2025 et sans contraintes', '2025-01-12', '10:30:00', '12:00:00'),
(4, 'Atelier #4 - Bye Bye Interdictions Hello Autorisations', 'Identifier les barrières que l\'on se met pour se permettre de nouvelles choses !', '2025-01-26', '17:30:00', '19:00:00'),
(5, 'Atelier #5 - Se donner de l\'amour', 'Se donner de l\'amour pour se faire du bien', '2025-02-09', '10:30:00', '12:00:00'),
(6, 'Atelier #6 - Trouver sa voie', 'Trouver sa voie d\'une manière différente en se reconnectant à soi', '2025-02-23', '10:30:00', '12:00:00'),
(7, 'Atelier #7 - Puissance féminine', 'Se reconnecter à sa puissance féminine', '2025-03-09', '10:30:00', '12:00:00'),
(8, 'Atelier #8', 'Atelier sur le thème de l\'équité femmes & hommes : Sortons des injonctions !', '2025-03-30', '17:30:00', '19:00:00'),
(9, 'Atelier #9', 'Atelier : thème à définir', '2025-04-06', '10:30:00', '12:00:00'),
(10, 'Atelier #10', 'Atelier : Membership et gestion d\'équipe', '2025-04-20', '17:30:00', '19:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `atelier_equite`
--

CREATE TABLE `atelier_equite` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `atelier_equite`
--

INSERT INTO `atelier_equite` (`id`, `nom`, `description`, `date`, `heure_debut`, `heure_fin`, `type`) VALUES
(1, 'Atelier #1 - Noël', 'Écrire sur Noël peu importe qu’on aime ou pas cette période', '2024-12-15', '21:30:00', '19:00:00', 'Saint-Omer'),
(2, 'Fresque de l\'équité', 'Atelier sur l\'équité pour sensibiliser les femmes et hommes et se mettre en action', '2024-10-15', '18:00:00', '20:30:00', 'Lille'),
(3, 'IAmRemarkable', 'Découverte de l\'importance de l\'autopromotion dans ta vie pro et perso', '2024-10-22', '19:00:00', '20:30:00', 'Marcq-en-Baroeul'),
(4, 'IAmRemarkable', 'Découverte de l\'importance de l\'autopromotion dans ta vie pro et perso', '2024-11-11', '10:30:00', '12:00:00', 'En ligne'),
(5, 'IAmRemarkable', 'Découverte de l\'importance de l\'autopromotion dans ta vie pro et perso', '2025-01-15', '13:00:00', '14:30:00', 'En ligne'),
(6, 'Superpouvoir des femmes', 'Atelier sur le superpouvoir des femmes pour performer', '2025-01-23', '10:00:00', '10:30:00', 'En ligne'),
(7, 'Superpouvoir des femmes', 'Atelier sur le superpouvoir des femmes pour performer', '2025-03-30', '10:00:00', '10:30:00', 'En ligne');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `note` tinyint(4) DEFAULT NULL CHECK (`note` between 1 and 5),
  `date_publication` timestamp NULL DEFAULT current_timestamp(),
  `texte` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `id_user`, `note`, `date_publication`, `texte`) VALUES
(7, 1, 5, '2025-03-10 14:52:56', 'Le coaching sur le leadership féminin m\'a vraiment aidée à prendre confiance en moi et à mieux m\'affirmer en entreprise. Merci pour cette belle expérience !'),
(8, 1, 4, '2025-03-10 14:52:56', 'L\'atelier \"Fresque de l\'équité\" était très inspirant ! J\'ai pris conscience de nombreux biais inconscients et je vais appliquer ces apprentissages dans mon travail.'),
(9, 1, 5, '2025-03-10 14:52:56', 'Le programme pour les femmes entrepreneures est une véritable révélation ! Grâce aux séances, j\'ai pu structurer mon projet et gagner en motivation.'),
(10, 1, 3, '2025-03-10 14:52:56', 'L\'atelier \"IAmRemarkable\" est intéressant, mais j\'aurais aimé plus d\'exemples concrets pour appliquer ces conseils au quotidien.'),
(11, 1, 5, '2025-03-10 14:52:56', 'Le cercle de femmes est un espace bienveillant où l\'on se sent écoutée et soutenue. J\'ai adoré pouvoir échanger avec d\'autres femmes dans des situations similaires.'),
(12, 1, 4, '2025-03-10 14:52:56', 'Le coaching individuel était très pertinent et adapté à mes besoins. J\'ai appris à mieux gérer mon stress et à exprimer mes idées avec plus d\'assurance.');

-- --------------------------------------------------------

--
-- Structure de la table `coaching`
--

CREATE TABLE `coaching` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `categorie` enum('collectif','individuel') NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `coaching`
--

INSERT INTO `coaching` (`id`, `titre`, `categorie`, `description`) VALUES
(1, 'Coaching Leadership féminin', 'individuel', 'Retrouvez la confiance en vous et renforcez votre leadership'),
(2, 'Coaching des IT girls', 'individuel', 'Vous évoluez dans la tech ? Retrouvez votre énergie et renforcez votre leadership.'),
(3, 'Coaching des femmes évoluant dans les environnements masculins', 'individuel', 'Venez prendre conscience de vos atouts et renforcer votre leadership.'),
(4, 'Programme pour les femmes entrepreneures', 'collectif', '6 séances de 2h pour retrouver son mojo, découvrir ses forces et se mettre en action !'),
(5, 'Programme pour les femmes se questionnant sur leur travail et projet professionnel', 'collectif', '6 séances de 2h pour réfléchir à son avenir professionnel'),
(6, 'Cercle de femmes', 'collectif', '1 séance de 2h pour déposer et résonner entre femmes sur des sujets de confiance en soi');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `user_id` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `date_publi` date NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`user_id`, `commentaire`, `date_publi`, `post_id`, `id`) VALUES
(7, 'commentaire de test', '2025-03-11', 1, 1),
(7, 'oui', '2025-03-11', 1, 2),
(1, 'TEST', '2025-03-11', 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `ebooks`
--

CREATE TABLE `ebooks` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `auteur` varchar(255) DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `fichier` varchar(255) NOT NULL,
  `date_publication` date DEFAULT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `nombre_pages` int(11) DEFAULT NULL,
  `format` varchar(50) DEFAULT 'PDF',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ebooks`
--

INSERT INTO `ebooks` (`id`, `titre`, `description`, `auteur`, `prix`, `image`, `fichier`, `date_publication`, `categorie`, `nombre_pages`, `format`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Ecris pour apaiser tes relations familiales', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'Audrey Rebout', 30.00, '../src/img/1.png', '', '2025-03-11', 'Livre', 300, 'PDF', 1, '2025-03-11 14:16:00', '2025-03-11 14:41:00'),
(2, 'Ecris pour booster ta confiance', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'Audrey Rebout', 30.00, '../src/img/2.png', '', '2025-03-11', 'Livre', 300, 'PDF', 1, '2025-03-11 14:16:50', '2025-03-11 14:41:14'),
(3, 'Ecris pour faire un choix', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'Audrey Rebout', 30.00, '../src/img/3.png', '', '2025-03-11', 'Livre', 300, 'PDF', 1, '2025-03-11 14:17:46', '2025-03-11 14:41:19'),
(4, 'Ecris pour mieux vivre ta maladie', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 'Audrey Rebout', 30.00, '../src/img/4.png', '', '2025-03-11', 'Livre', 300, 'PDF', 1, '2025-03-11 14:18:35', '2025-03-11 14:41:23');

-- --------------------------------------------------------

--
-- Structure de la table `ebooks_achats`
--

CREATE TABLE `ebooks_achats` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_ebook` int(11) NOT NULL,
  `date_achat` timestamp NULL DEFAULT current_timestamp(),
  `prix` decimal(10,2) NOT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `path_pdf` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `history_user`
--

CREATE TABLE `history_user` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_event` int(11) NOT NULL,
  `event_type` enum('atelier_ecriture','atelier_equite','coaching') NOT NULL,
  `path_pdf` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `heure_debut` time DEFAULT NULL,
  `heure_fin` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `history_user`
--

INSERT INTO `history_user` (`id`, `id_user`, `id_event`, `event_type`, `path_pdf`, `date`, `prix`, `heure_debut`, `heure_fin`) VALUES
(34, 1, 7, 'atelier_equite', 'invoices/facture_pi_3R1UoD01jgFsFXME02jECtnH.pdf', '2025-03-11', 50.00, NULL, NULL),
(44, 1, 2, 'atelier_equite', 'invoices/facture_pi_3R1SWR01jgFsFXME1iAFla3J.pdf', '2025-03-11', 50.00, NULL, NULL),
(45, 1, 7, 'atelier_ecriture', 'invoices/facture_pi_3R1UoD01jgFsFXME02jECtnH.pdf', '2025-03-11', 50.00, NULL, NULL),
(48, 1, 7, 'atelier_ecriture', 'invoices/facture_pi_3R1UoD01jgFsFXME02jECtnH.pdf', '2025-03-21', 50.00, NULL, NULL),
(49, 1, 1, 'coaching', 'invoices/facture_coaching_pi_3R1UqR01jgFsFXME1dDtNKW1.pdf', '2025-03-11', 50.00, NULL, NULL),
(50, 1, 8, 'atelier_ecriture', 'invoices/facture_pi_3R1T3M01jgFsFXME0Ic7Ij7j.pdf', '2025-03-08', 50.00, NULL, NULL),
(51, 1, 7, 'atelier_ecriture', 'invoices/facture_pi_3R1UoD01jgFsFXME02jECtnH.pdf', '2025-03-09', 50.00, NULL, NULL),
(52, 1, 7, 'atelier_ecriture', 'invoices/facture_pi_3R1UoD01jgFsFXME02jECtnH.pdf', '2025-03-11', 50.00, NULL, NULL),
(53, 1, 9, 'atelier_ecriture', 'invoices/facture_pi_3R1TDi01jgFsFXME0cH63fgm.pdf', '2025-03-11', 50.00, NULL, NULL),
(54, 1, 1, 'coaching', 'invoices/facture_coaching_pi_3R1UqR01jgFsFXME1dDtNKW1.pdf', '2025-03-11', 50.00, '09:00:00', '10:00:00'),
(55, 1, 1, 'atelier_equite', 'invoices/facture_coaching_pi_3R1UqR01jgFsFXME1dDtNKW1.pdf', '2025-03-11', 50.00, '16:00:00', '17:00:00'),
(56, 1, 1, 'atelier_ecriture', 'invoices/facture_coaching_pi_3R1UqR01jgFsFXME1dDtNKW1.pdf', '2025-03-11', 50.00, '08:00:00', '09:00:00'),
(57, 1, 7, 'atelier_ecriture', 'invoices/facture_pi_3R1UoD01jgFsFXME02jECtnH.pdf', '2025-03-11', 50.00, NULL, NULL),
(58, 1, 7, 'atelier_ecriture', 'invoices/facture_pi_3R1UoD01jgFsFXME02jECtnH.pdf', '2025-03-11', 50.00, NULL, NULL),
(59, 1, 1, 'coaching', 'invoices/facture_coaching_pi_3R1UqR01jgFsFXME1dDtNKW1.pdf', '2025-03-11', 50.00, '14:00:00', '15:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `indisponibilites`
--

CREATE TABLE `indisponibilites` (
  `id` int(11) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `motif` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `indisponibilites`
--

INSERT INTO `indisponibilites` (`id`, `date_debut`, `date_fin`, `motif`, `created_at`, `updated_at`) VALUES
(2, '2025-03-12 10:00:00', '2025-03-12 11:00:00', 'ez', '2025-03-11 10:22:26', '2025-03-11 10:22:26'),
(4, '2025-03-13 10:00:00', '2025-03-13 11:00:00', 'RDV', '2025-03-11 12:38:01', '2025-03-11 12:38:01');

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `date_publie` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `titre`, `contenu`, `date_publie`) VALUES
(1, 'L’importance de l’équité femmes-hommes', 'L’équité entre les femmes et les hommes est essentielle pour une société plus juste et inclusive. Cela signifie que je sais pas j\'ecris des chose pour un exemple en sah', '2025-03-10 12:57:53'),
(5, 'la difference de salaire entre hommes et femmes', 'C HONTEUX', '2025-03-11 14:41:41');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `profession` varchar(150) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_inscription` timestamp NULL DEFAULT current_timestamp(),
  `is_verified` tinyint(1) DEFAULT 0,
  `role_user` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `date_naissance`, `profession`, `email`, `telephone`, `password`, `date_inscription`, `is_verified`, `role_user`) VALUES
(1, 'test', 'test', '2025-03-10', '', 'test@test.fr', 'test', '202cb962ac59075b964b07152d234b70', '2025-03-10 14:25:06', 0, 'admin'),
(2, 'Lefebvre', 'Jason', '2025-03-10', 'no', 'jason.lefebvre@ynov.com', '0621376103', '202cb962ac59075b964b07152d234b70', '2025-03-10 15:18:36', 0, 'admin'),
(5, 'Oui', 'AZERTY', '2025-02-24', 'no', 'jason.lefebvre.contact@gmail.com', '0621376110', '18bc6fec3b451b098c17a04b094f3363', '2025-03-10 19:54:07', 0, 'user'),
(7, 'Admin', 'admin', '2025-03-03', 'admin', 'admin@admin.fr', '0621376112', '552b2ebe774bb5aaa0ad2021da259d22', '2025-03-11 00:24:41', 0, 'user');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `url_video` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `titre`, `description`, `url_video`) VALUES
(1, 'Le superpouvoir des femmes pour performer', 'Découvre ici le superpouvoir des femmes pour performer (30min) ', 'https://www.youtube.com/watch?v=g-JsJF945MY');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `atelier_ecriture`
--
ALTER TABLE `atelier_ecriture`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `atelier_equite`
--
ALTER TABLE `atelier_equite`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `coaching`
--
ALTER TABLE `coaching`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ebooks_categorie` (`categorie`);

--
-- Index pour la table `ebooks_achats`
--
ALTER TABLE `ebooks_achats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ebooks_achats_user` (`id_user`),
  ADD KEY `idx_ebooks_achats_ebook` (`id_ebook`);

--
-- Index pour la table `history_user`
--
ALTER TABLE `history_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `indisponibilites`
--
ALTER TABLE `indisponibilites`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `telephone` (`telephone`);

--
-- Index pour la table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `atelier_ecriture`
--
ALTER TABLE `atelier_ecriture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `atelier_equite`
--
ALTER TABLE `atelier_equite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `coaching`
--
ALTER TABLE `coaching`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `ebooks_achats`
--
ALTER TABLE `ebooks_achats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `history_user`
--
ALTER TABLE `history_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `indisponibilites`
--
ALTER TABLE `indisponibilites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ebooks_achats`
--
ALTER TABLE `ebooks_achats`
  ADD CONSTRAINT `ebooks_achats_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ebooks_achats_ibfk_2` FOREIGN KEY (`id_ebook`) REFERENCES `ebooks` (`id`);

--
-- Contraintes pour la table `history_user`
--
ALTER TABLE `history_user`
  ADD CONSTRAINT `history_user_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
