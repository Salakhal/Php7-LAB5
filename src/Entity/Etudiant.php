<?php
declare(strict_types=1);
namespace App\Entity;

class Etudiant
{
    private $id;
    private $cne;
    private $nom;
    private $prenom;
    private $email;
    private $filiereId;

    public function __construct(?int $id, string $cne, string $nom, string $prenom, string $email, int $filiereId)
    {
        $this->id = $id;
        $this->setCne($cne);
        $this->setNom($nom);
        $this->setPrenom($prenom);
        $this->setEmail($email);
        $this->setFiliereId($filiereId);
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getCne(): string { return $this->cne; }
    public function setCne(string $cne): void
    {
        if (empty(trim($cne))) { throw new \InvalidArgumentException('Le CNE est invalide.'); }
        $this->cne = trim($cne);
    }

    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): void
    {
        if (empty(trim($nom))) { throw new \InvalidArgumentException('Le nom est manquant.'); }
        $this->nom = trim($nom);
    }

    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): void
    {
        if (empty(trim($prenom))) { throw new \InvalidArgumentException('Le prénom est manquant.'); }
        $this->prenom = trim($prenom);
    }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void
    {
        $nettoye = trim($email);
        if (!filter_var($nettoye, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Le format de l\'email est incorrect.');
        }
        $this->email = $nettoye;
    }

    public function getFiliereId(): int { return $this->filiereId; }
    public function setFiliereId(int $filiereId): void
    {
        if ($filiereId < 1) { throw new \InvalidArgumentException('Identifiant de filière non valide.'); }
        $this->filiereId = $filiereId;
    }
}