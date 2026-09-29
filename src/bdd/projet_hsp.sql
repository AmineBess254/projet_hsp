CREATE DATABASE IF NOT EXISTS `projet_hsp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `projet_hsp`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

DROP TABLE IF EXISTS `canal`;
CREATE TABLE `canal` (
                         `id_canal` int NOT NULL AUTO_INCREMENT,
                         `nom_canal` varchar(100) NOT NULL,
                         `date_heure` datetime DEFAULT CURRENT_TIMESTAMP,
                         PRIMARY KEY (`id_canal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `candidature`;
CREATE TABLE `candidature` (
                               `id_candidature` int NOT NULL AUTO_INCREMENT,
                               `motivation` text,
                               `date_candidature` datetime DEFAULT CURRENT_TIMESTAMP,
                               `statut_candidature` enum('en_attente','masquee') NOT NULL DEFAULT 'en_attente',
                               `ref_utilisateur` int NOT NULL,
                               `ref_offre` int NOT NULL,
                               PRIMARY KEY (`id_candidature`),
                               KEY `fk_candidature_utilisateur` (`ref_utilisateur`),
                               KEY `fk_candidature_offre` (`ref_offre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `entreprise`;
CREATE TABLE `entreprise` (
                              `id_entreprise` int NOT NULL AUTO_INCREMENT,
                              `nom` varchar(150) NOT NULL,
                              `site_web` varchar(255) NOT NULL,
                              `adresse` varchar(255) DEFAULT NULL,
                              PRIMARY KEY (`id_entreprise`),
                              UNIQUE KEY `site_web` (`site_web`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `etablissement`;
CREATE TABLE `etablissement` (
                                 `id_etablissement` int NOT NULL AUTO_INCREMENT,
                                 `nom` varchar(150) NOT NULL,
                                 `adresse` varchar(255) DEFAULT NULL,
                                 `site_web` varchar(255) NOT NULL,
                                 PRIMARY KEY (`id_etablissement`),
                                 UNIQUE KEY `site_web` (`site_web`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE `etudiant` (
                            `id_utilisateur` int NOT NULL,
                            `cv` varchar(255) DEFAULT NULL,
                            `formation` varchar(150) DEFAULT NULL,
                            PRIMARY KEY (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE `evenement` (
                             `id_evenement` int NOT NULL AUTO_INCREMENT,
                             `type` varchar(100) DEFAULT NULL,
                             `titre` varchar(150) NOT NULL,
                             `description` text,
                             `adresse` varchar(255) DEFAULT NULL,
                             `element_requis` varchar(255) DEFAULT NULL,
                             `nb_place` int DEFAULT NULL,
                             `ref_medecin` int DEFAULT NULL,
                             `ref_partenaire` int DEFAULT NULL,
                             PRIMARY KEY (`id_evenement`),
                             KEY `fk_evenement_medecin` (`ref_medecin`),
                             KEY `fk_evenement_partenaire` (`ref_partenaire`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `gestionnaire`;
CREATE TABLE `gestionnaire` (
                                `id_utilisateur` int NOT NULL,
                                `id_gestionnaire_createur` int DEFAULT NULL,
                                PRIMARY KEY (`id_utilisateur`),
                                KEY `fk_gestionnaire_createur` (`id_gestionnaire_createur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `hopital`;
CREATE TABLE `hopital` (
                           `id_hopital` int NOT NULL AUTO_INCREMENT,
                           `nom` varchar(150) NOT NULL,
                           `adresse` varchar(255) NOT NULL,
                           PRIMARY KEY (`id_hopital`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `inscription`;
CREATE TABLE `inscription` (
                               `id_inscription` int NOT NULL AUTO_INCREMENT,
                               `statut_inscription` enum('en_attente','accepte','refuse') NOT NULL DEFAULT 'en_attente',
                               `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
                               `ref_evenement` int NOT NULL,
                               `ref_utilisateur` int NOT NULL,
                               PRIMARY KEY (`id_inscription`),
                               KEY `fk_inscription_evenement` (`ref_evenement`),
                               KEY `fk_inscription_utilisateur` (`ref_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `medecin`;
CREATE TABLE `medecin` (
                           `id_utilisateur` int NOT NULL,
                           `specialite` varchar(100) DEFAULT NULL,
                           `ref_hopital` int NOT NULL,
                           `ref_etablissement` int DEFAULT NULL,
                           PRIMARY KEY (`id_utilisateur`),
                           KEY `fk_medecin_hopital` (`ref_hopital`),
                           KEY `fk_medecin_etablissement` (`ref_etablissement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `offre`;
CREATE TABLE `offre` (
                         `id_offre` int NOT NULL AUTO_INCREMENT,
                         `type_offre` enum('stage','alternance','cdd','cdi') NOT NULL,
                         `titre` varchar(150) NOT NULL,
                         `date` date NOT NULL,
                         `description` text,
                         `missions` text,
                         `salaire` decimal(10,2) DEFAULT NULL,
                         `etat` enum('ouvert','cloture') NOT NULL DEFAULT 'ouvert',
                         `ref_utilisateur` int NOT NULL,
                         PRIMARY KEY (`id_offre`),
                         KEY `fk_offre_utilisateur` (`ref_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `partenaire`;
CREATE TABLE `partenaire` (
                              `id_utilisateur` int NOT NULL,
                              `poste` varchar(100) DEFAULT NULL,
                              `ref_entreprise` int DEFAULT NULL,
                              PRIMARY KEY (`id_utilisateur`),
                              KEY `fk_partenaire_entreprise` (`ref_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
                        `id_post` int NOT NULL AUTO_INCREMENT,
                        `contenu` text NOT NULL,
                        `date_heure` datetime DEFAULT CURRENT_TIMESTAMP,
                        `titre` varchar(150) DEFAULT NULL,
                        `ref_canal` int NOT NULL,
                        PRIMARY KEY (`id_post`),
                        KEY `fk_post_canal` (`ref_canal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE `reponse` (
                           `id_reponse` int NOT NULL AUTO_INCREMENT,
                           `contenu` text NOT NULL,
                           `date_heure` datetime DEFAULT CURRENT_TIMESTAMP,
                           `ref_utilisateur` int NOT NULL,
                           `ref_post` int NOT NULL,
                           PRIMARY KEY (`id_reponse`),
                           KEY `fk_reponse_utilisateur` (`ref_utilisateur`),
                           KEY `fk_reponse_post` (`ref_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE `utilisateur` (
                               `id_utilisateur` int NOT NULL AUTO_INCREMENT,
                               `nom` varchar(100) NOT NULL,
                               `prenom` varchar(100) NOT NULL,
                               `email` varchar(150) NOT NULL,
                               `mdp` varchar(255) NOT NULL,
                               `statut_validation` enum('en_attente','valide','refuse') NOT NULL DEFAULT 'en_attente',
                               `ref_gestionnaire` int DEFAULT NULL,
                               PRIMARY KEY (`id_utilisateur`),
                               UNIQUE KEY `email` (`email`),
                               KEY `fk_utilisateur_gestionnaire` (`ref_gestionnaire`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- CLÉS ÉTRANGÈRES
ALTER TABLE `candidature`
    ADD CONSTRAINT `fk_candidature_offre` FOREIGN KEY (`ref_offre`) REFERENCES `offre` (`id_offre`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_candidature_utilisateur` FOREIGN KEY (`ref_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

ALTER TABLE `etudiant`
    ADD CONSTRAINT `fk_etudiant_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

ALTER TABLE `evenement`
    ADD CONSTRAINT `fk_evenement_medecin` FOREIGN KEY (`ref_medecin`) REFERENCES `medecin` (`id_utilisateur`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_evenement_partenaire` FOREIGN KEY (`ref_partenaire`) REFERENCES `partenaire` (`id_utilisateur`) ON DELETE SET NULL;

ALTER TABLE `gestionnaire`
    ADD CONSTRAINT `fk_gestionnaire_createur` FOREIGN KEY (`id_gestionnaire_createur`) REFERENCES `gestionnaire` (`id_utilisateur`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_gestionnaire_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

ALTER TABLE `inscription`
    ADD CONSTRAINT `fk_inscription_evenement` FOREIGN KEY (`ref_evenement`) REFERENCES `evenement` (`id_evenement`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inscription_utilisateur` FOREIGN KEY (`ref_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

ALTER TABLE `medecin`
    ADD CONSTRAINT `fk_medecin_etablissement` FOREIGN KEY (`ref_etablissement`) REFERENCES `etablissement` (`id_etablissement`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_medecin_hopital` FOREIGN KEY (`ref_hopital`) REFERENCES `hopital` (`id_hopital`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_medecin_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

ALTER TABLE `offre`
    ADD CONSTRAINT `fk_offre_utilisateur` FOREIGN KEY (`ref_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

ALTER TABLE `partenaire`
    ADD CONSTRAINT `fk_partenaire_entreprise` FOREIGN KEY (`ref_entreprise`) REFERENCES `entreprise` (`id_entreprise`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_partenaire_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

ALTER TABLE `post`
    ADD CONSTRAINT `fk_post_canal` FOREIGN KEY (`ref_canal`) REFERENCES `canal` (`id_canal`) ON DELETE CASCADE;

ALTER TABLE `reponse`
    ADD CONSTRAINT `fk_reponse_post` FOREIGN KEY (`ref_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reponse_utilisateur` FOREIGN KEY (`ref_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

ALTER TABLE `utilisateur`
    ADD CONSTRAINT `fk_utilisateur_gestionnaire` FOREIGN KEY (`ref_gestionnaire`) REFERENCES `gestionnaire` (`id_utilisateur`) ON DELETE SET NULL;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;