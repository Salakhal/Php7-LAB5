-- Initialisation de la base de données PDO Lab
DROP DATABASE IF EXISTS gestion_etudiants_pdo;
CREATE DATABASE gestion_etudiants_pdo DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE gestion_etudiants_pdo;

-- Création de la table filiere
CREATE TABLE filiere (
  id INT NOT NULL AUTO_INCREMENT,
  code VARCHAR(16) NOT NULL,
  libelle VARCHAR(100) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT unique_code_fil UNIQUE (code)
) ENGINE=InnoDB;

-- Création de la table etudiant
CREATE TABLE etudiant (
  id INT NOT NULL AUTO_INCREMENT,
  cne VARCHAR(20) NOT NULL,
  nom VARCHAR(80) NOT NULL,
  prenom VARCHAR(80) NOT NULL,
  email VARCHAR(120) NOT NULL,
  filiere_id INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_etu_filiere FOREIGN KEY (filiere_id) REFERENCES filiere(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT unique_cne_etu UNIQUE (cne),
  CONSTRAINT unique_email_etu UNIQUE (email)
) ENGINE=InnoDB;

-- Quelques données pour tester
INSERT INTO filiere(code, libelle) VALUES
('DEV', 'Développement Informatique'),
('RESEAU', 'Réseaux et Systèmes');

INSERT INTO etudiant(cne, nom, prenom, email, filiere_id) VALUES
('CNE112233', 'Alaoui', 'Youssef', 'youssef.alaoui@email.ma', 1);