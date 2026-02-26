<?php
declare(strict_types=1);
namespace App\Dao;

use PDO; 
use PDOException;
use App\Entity\Etudiant;
use App\Database\DBConnection;
use App\Log\Logger;

class EtudiantDao
{
    private $log;

    public function __construct(Logger $log)
    {
        $this->log = $log;
    }

    public function insert(Etudiant $etu): int
    {
        $req = 'INSERT INTO etudiant (cne, nom, prenom, email, filiere_id) VALUES (:cne, :nom, :pre, :em, :fid)';
        try {
            $base = DBConnection::get();
            $prep = $base->prepare($req);
            $prep->bindValue(':cne', $etu->getCne(), PDO::PARAM_STR);
            $prep->bindValue(':nom', $etu->getNom(), PDO::PARAM_STR);
            $prep->bindValue(':pre', $etu->getPrenom(), PDO::PARAM_STR);
            $prep->bindValue(':em', $etu->getEmail(), PDO::PARAM_STR);
            $prep->bindValue(':fid', $etu->getFiliereId(), PDO::PARAM_INT);
            $prep->execute();
            
            $idGenere = (int)$base->lastInsertId();
            $etu->setId($idGenere);
            return $idGenere;
        } catch (PDOException $erreur) {
            $this->log->error($erreur->getMessage(), ['email' => $etu->getEmail()]);
            throw $erreur;
        }
    }

    public function update(Etudiant $etu): bool
    {
        $req = 'UPDATE etudiant SET cne=:cne, nom=:nom, prenom=:pre, email=:em, filiere_id=:fid WHERE id=:id';
        try {
            $base = DBConnection::get();
            $prep = $base->prepare($req);
            
            $v_id = $etu->getId(); 
            $v_cne = $etu->getCne(); 
            $v_nom = $etu->getNom(); 
            $v_pre = $etu->getPrenom(); 
            $v_em = $etu->getEmail(); 
            $v_fid = $etu->getFiliereId();
            
            $prep->bindParam(':id', $v_id, PDO::PARAM_INT);
            $prep->bindParam(':cne', $v_cne, PDO::PARAM_STR);
            $prep->bindParam(':nom', $v_nom, PDO::PARAM_STR);
            $prep->bindParam(':pre', $v_pre, PDO::PARAM_STR);
            $prep->bindParam(':em', $v_em, PDO::PARAM_STR);
            $prep->bindParam(':fid', $v_fid, PDO::PARAM_INT);
            $prep->execute();
            
            return $prep->rowCount() > 0;
        } catch (PDOException $erreur) {
            $this->log->error($erreur->getMessage(), ['id' => $etu->getId()]);
            throw $erreur;
        }
    }

    public function delete(int $idEtudiant): bool
    {
        $req = 'DELETE FROM etudiant WHERE id = :id';
        try {
            $prep = DBConnection::get()->prepare($req);
            $prep->bindValue(':id', $idEtudiant, PDO::PARAM_INT);
            $prep->execute();
            return $prep->rowCount() > 0;
        } catch (PDOException $erreur) {
            $this->log->error($erreur->getMessage(), ['id' => $idEtudiant]);
            throw $erreur;
        }
    }

    public function findById(int $idEtudiant): ?Etudiant
    {
        $req = 'SELECT * FROM etudiant WHERE id = :id LIMIT 1';
        try {
            $prep = DBConnection::get()->prepare($req);
            $prep->bindValue(':id', $idEtudiant, PDO::PARAM_INT);
            $prep->execute();
            $data = $prep->fetch();
            
            if (!$data) return null;
            
            return new Etudiant((int)$data['id'], $data['cne'], $data['nom'], $data['prenom'], $data['email'], (int)$data['filiere_id']);
        } catch (PDOException $erreur) {
            $this->log->error($erreur->getMessage(), ['id' => $idEtudiant]);
            throw $erreur;
        }
    }

    public function findAll(): array
    {
        $req = 'SELECT * FROM etudiant ORDER BY nom ASC';
        try {
            $prep = DBConnection::get()->query($req);
            $resultats = $prep->fetchAll();
            $etudiants = [];
            foreach ($resultats as $d) {
                $etudiants[] = new Etudiant((int)$d['id'], $d['cne'], $d['nom'], $d['prenom'], $d['email'], (int)$d['filiere_id']);
            }
            return $etudiants;
        } catch (PDOException $erreur) {
            $this->log->error($erreur->getMessage());
            throw $erreur;
        }
    }
}