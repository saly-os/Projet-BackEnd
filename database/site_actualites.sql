-- Encodage
SET NAMES utf8mb4;

-- Recréation de la base
DROP DATABASE IF EXISTS site_actualites;
CREATE DATABASE IF NOT EXISTS site_actualites
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;
USE site_actualites;

-- ======================
-- TABLE UTILISATEURS
-- ======================
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    login VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('editeur','administrateur') NOT NULL
);

-- ======================
-- TABLE CATEGORIES
-- ======================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

-- ======================
-- TABLE ARTICLES
-- ======================
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description_courte TEXT NOT NULL,
    contenu LONGTEXT NOT NULL,
    categorie_id INT NOT NULL,
    auteur_id INT NOT NULL,
    date_publication DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE RESTRICT
);

-- ======================
-- DONNÉES UTILISATEURS
-- ======================

INSERT INTO utilisateurs (prenom, nom, login, mot_de_passe, role) VALUES
('Admin2', 'Principal', 'admin2', '$2y$10$f8Wa9Xl3.eS4gOJeC2WaPO1kPy5NF/U9PyPYeJN0jaJC/eD2BOwK6', 'administrateur'),
('Saly', 'Editeur', 'saly', '$2y$10$7m9VM0XQluKQ/KD7to81Cu9y0i7F08ocdCQ2a/p6aFmslXTcN7x9i', 'editeur');

-- ======================
-- DONNÉES CATÉGORIES
-- ======================
INSERT INTO categories (nom) VALUES
('Tech'),
('Santé'),
('Sport'),
('Géopolitique'),
('Education');

-- ======================
-- DONNÉES ARTICLES
-- ======================
INSERT INTO articles (titre, description_courte, contenu, categorie_id, auteur_id) VALUES
('Nouvelle application mobile', 'Une application innovante révolutionne le marché.', 'Contenu complet de l’article sur la technologie...', 1, 2),
('Conseils santé', 'Comment rester en bonne santé au quotidien.', 'Contenu détaillé sur la santé...', 2, 2),
('Résultats sportifs', 'Résumé du dernier match.', 'Contenu détaillé du match...', 3, 2),
('Tension Israël-Iran', 'Etat actuel du conflit','Influence des Etats-Unis', 4, 2),
('L’éducation en évolution', 'Les nouvelles méthodes d’apprentissage.', 'Contenu sur l’éducation moderne...', 5, 2);
