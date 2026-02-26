<?php
declare(strict_types=1);

// 1. Configuration de base (Optionnel mais très utile pour voir les erreurs en dev)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// 2. Définition du fuseau horaire (très important pour le Logger)
date_default_timezone_set('Africa/Casablanca');

// 3. Chargeur automatique de classes (Autoloader)
spl_autoload_register(function ($nomDeLaClasse) {
    // Le namespace principal de notre projet
    $prefixeNamespace = 'App\\'; 
    
    // Le dossier où se trouvent toutes nos classes PHP
    $dossierSource = __DIR__ . '/src/'; 
    
    // On vérifie si la classe qu'on essaie de charger commence bien par "App\"
    if (strpos($nomDeLaClasse, $prefixeNamespace) === 0) {
        
        // On enlève "App\" pour avoir juste le reste (ex: "Entity\Etudiant")
        $cheminSansPrefixe = substr($nomDeLaClasse, strlen($prefixeNamespace));
        
        // On remplace les antislashs "\" par des slashs "/" pour le chemin du fichier
        $cheminCompletDuFichier = $dossierSource . str_replace('\\', '/', $cheminSansPrefixe) . '.php';
        
        // Si le fichier existe physiquement, on l'inclut
        if (file_exists($cheminCompletDuFichier)) {
            require_once $cheminCompletDuFichier;
        }
    }
});