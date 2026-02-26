<?php
declare(strict_types=1);

$serveur = getenv('DB_HOST') ?: 'localhost';
$portDB = getenv('DB_PORT') ?: '3306';
$base = getenv('DB_NAME') ?: 'gestion_etudiants_pdo';

return [
    'connexion_string' => "mysql:host={$serveur};port={$portDB};dbname={$base};charset=utf8mb4",
    'identifiant' => getenv('DB_USER') ?: 'root',
    'mot_de_passe' => getenv('DB_PASS') ?: '',
    'parametres_pdo' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];