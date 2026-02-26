<?php
declare(strict_types=1);
namespace App\Database;

use PDO; 
use PDOException; 
use App\Log\Logger;

class DBConnection
{
    private static $instancePdo = null;
    private static $journalier;

    public static function init(Logger $journalier): void
    {
        self::$journalier = $journalier;
    }

    public static function get(): PDO
    {
        if (self::$instancePdo !== null) {
            return self::$instancePdo;
        }
        
        $configuration = require __DIR__ . '/../../config/db.php';
        
        try {
            self::$instancePdo = new PDO(
                $configuration['connexion_string'], 
                $configuration['identifiant'], 
                $configuration['mot_de_passe'], 
                $configuration['parametres_pdo']
            );
            self::$instancePdo->exec('SET NAMES utf8mb4');
            return self::$instancePdo;
            
        } catch (PDOException $erreur) {
            if (self::$journalier) {
                self::$journalier->error('Echec de la connexion à la BDD', [
                    'source' => __METHOD__, 
                    'message' => $erreur->getMessage()
                ]);
            }
            throw $erreur; 
        }
    }
}