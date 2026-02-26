<?php
declare(strict_types=1);
namespace App\Dao;

use PDO; 
use PDOException;
use App\Entity\Filiere;
use App\Database\DBConnection;
use App\Log\Logger;

class FiliereDao
{
    private $log;

    public function __construct(Logger $log)
    {
        $this->log = $log;
    }

    public function insert(Filiere $filiere): int
    {
        $requete = 'INSERT INTO filiere (code, libelle) VALUES (:c, :l)';
        try {
            $base = DBConnection::get();
            $prep = $base->prepare($requete);
            $prep->bindValue(':c', $filiere->getCode(), PDO::PARAM_STR);
            $prep->bindValue(':l', $filiere->getLibelle(), PDO::PARAM_STR);
            $prep->execute();
            
            $nouvelId = (int)$base->lastInsertId();
            $filiere->setId($nouvelId);
            return $nouvelId;
        } catch (PDOException $err) {
            $this->log->error($err->getMessage(), ['action' => 'insert_filiere']);
            throw $err;
        }
    }

    public function update(Filiere $filiere): bool
    {
        $requete = 'UPDATE filiere SET code = :c, libelle = :l WHERE id = :i';
        try {
            $base = DBConnection::get();
            $prep = $base->prepare($requete);
            
            $idFil = $filiere->getId(); 
            $codeFil = $filiere->getCode(); 
            $libFil = $filiere->getLibelle();
            
            $prep->bindParam(':i', $idFil, PDO::PARAM_INT);
            $prep->bindParam(':c', $codeFil, PDO::PARAM_STR);
            $prep->bindParam(':l', $libFil, PDO::PARAM_STR);
            $prep->execute();
            
            return $prep->rowCount() > 0;
        } catch (PDOException $err) {
            $this->log->error($err->getMessage(), ['action' => 'update_filiere', 'id' => $filiere->getId()]);
            throw $err;
        }
    }

    public function delete(int $identifiant): bool
    {
        $requete = 'DELETE FROM filiere WHERE id = :i';
        try {
            $prep = DBConnection::get()->prepare($requete);
            $prep->bindValue(':i', $identifiant, PDO::PARAM_INT);
            $prep->execute();
            return $prep->rowCount() > 0;
        } catch (PDOException $err) {
            $this->log->error($err->getMessage(), ['action' => 'delete_filiere', 'id' => $identifiant]);
            throw $err;
        }
    }

    public function findById(int $identifiant): ?Filiere
    {
        $requete = 'SELECT * FROM filiere WHERE id = :i LIMIT 1';
        try {
            $prep = DBConnection::get()->prepare($requete);
            $prep->bindValue(':i', $identifiant, PDO::PARAM_INT);
            $prep->execute();
            $resultat = $prep->fetch();
            
            return $resultat ? new Filiere((int)$resultat['id'], $resultat['code'], $resultat['libelle']) : null;
        } catch (PDOException $err) {
            $this->log->error($err->getMessage(), ['action' => 'find_filiere']);
            throw $err;
        }
    }

    public function findAll(): array
    {
        $requete = 'SELECT * FROM filiere ORDER BY id DESC';
        try {
            $prep = DBConnection::get()->query($requete);
            $donnees = $prep->fetchAll();
            $liste = [];
            foreach ($donnees as $ligne) {
                $liste[] = new Filiere((int)$ligne['id'], $ligne['code'], $ligne['libelle']);
            }
            return $liste;
        } catch (PDOException $err) {
            $this->log->error($err->getMessage(), ['action' => 'findall_filiere']);
            throw $err;
        }
    }
}