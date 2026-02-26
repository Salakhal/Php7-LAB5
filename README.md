# Lab 5 : Gestion des Étudiants avec PHP Data Objects (PDO) et DAO

## 📝 Description du Projet
Ce projet est une application PHP implémentant l'architecture **DAO (Data Access Object)** pour gérer une base de données MySQL via **PDO**. 
L'objectif principal de ce laboratoire est de manipuler des données (CRUD), de gérer les exceptions de la base de données de manière silencieuse via un système de **Logs**, et d'utiliser les **Transactions** pour garantir l'intégrité des données.

## ⚙️ Fonctionnalités Implémentées
- **Architecture Multi-couches :** Séparation claire entre les Entités (`Entity`) et l'accès aux données (`Dao`).
- **Autoloading :** Chargement automatique des classes PHP via le script `bootstrap.php` (sans utiliser Composer).
- **Design Pattern Singleton :** Classe `DBConnection` permettant d'instancier une seule et unique connexion PDO à la base de données.
- **Opérations CRUD complètes :** Ajout, lecture, modification et suppression pour les `Etudiant` et `Filiere`.
- **Gestion des Logs :** Enregistrement des erreurs SQL (`PDOException`) dans le fichier `logs/pdo_errors.log` sans bloquer l'application.
- **Transactions :** Sécurisation des insertions multiples (Filière + Étudiant) avec `commit()` et `rollBack()`.

## 📂 Structure du Projet
```text
PhpProject5/
│
├── config/
│   └── db.php                 # Configuration de la base de données
├── logs/
│   └── pdo_errors.log         # Fichier généré automatiquement pour tracer les erreurs SQL
├── sql/
│   └── 001_create_db.sql      # Script de création de la base de données et des tables
├── src/
│   ├── Dao/
│   │   ├── EtudiantDao.php    # Requêtes SQL pour la table etudiant
│   │   └── FiliereDao.php     # Requêtes SQL pour la table filiere
│   ├── Database/
│   │   └── DBConnection.php   # Singleton pour la connexion PDO
│   ├── Entity/
│   │   ├── Etudiant.php       # Classe représentant un étudiant (Getters/Setters)
│   │   └── Filiere.php        # Classe représentant une filière (Getters/Setters)
│   └── Log/
│       └── Logger.php         # Classe pour la gestion des fichiers de logs
│
├── bootstrap.php              # Fichier d'initialisation et Autoloader
├── test_dao.php               # Script principal de test (Point d'entrée)
└── README.md                  # Documentation du projet
```
 ## file  pdo_errors.log 
 <img width="1909" height="418" alt="image" src="https://github.com/user-attachments/assets/a93f9fa4-9183-42a9-a0a3-5f89ce486236" />



<img width="820" height="552" alt="image" src="https://github.com/user-attachments/assets/93f3a118-059f-4c19-81a8-6fcd89077e8b" />
