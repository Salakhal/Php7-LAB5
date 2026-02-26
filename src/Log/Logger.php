<?php
declare(strict_types=1);
namespace App\Log;

class Logger
{
    private $fichierLog;

    public function __construct(string $chemin)
    {
        $this->fichierLog = $chemin;
        $dossier = dirname($chemin);
        
        if (!is_dir($dossier)) { 
            @mkdir($dossier, 0777, true); 
        }
        if (!file_exists($chemin)) { 
            @touch($chemin); 
        }
    }

    public function error(string $msg, array $infos = []): void
    {
        $maintenant = new \DateTime();
        $timestamp = $maintenant->format('Y-m-d H:i:s');
        $details = json_encode($infos, JSON_UNESCAPED_UNICODE);
        
        $texteLog = "[{$timestamp}] ERREUR : {$msg} | details={$details}" . PHP_EOL;
        
        @file_put_contents($this->fichierLog, $texteLog, FILE_APPEND);
    }
}