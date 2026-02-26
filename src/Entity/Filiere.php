<?php
declare(strict_types=1);
namespace App\Entity;

class Filiere
{
    private $id;
    private $code;
    private $libelle;

    public function __construct(?int $id, string $code, string $libelle)
    {
        $this->id = $id;
        $this->setCode($code);
        $this->setLibelle($libelle);
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getCode(): string { return $this->code; }
    public function setCode(string $code): void
    {
        $valeur = trim($code);
        if (empty($valeur)) { throw new \InvalidArgumentException('Le code ne peut pas être vide.'); }
        $this->code = $valeur;
    }

    public function getLibelle(): string { return $this->libelle; }
    public function setLibelle(string $libelle): void
    {
        $valeur = trim($libelle);
        if (empty($valeur)) { throw new \InvalidArgumentException('Le libellé est obligatoire.'); }
        $this->libelle = $valeur;
    }
}