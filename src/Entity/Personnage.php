<?php

namespace App\Entity;

use App\Repository\PersonnageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonnageRepository::class)]
class Personnage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $carnetDeVoyage = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $inventaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCarnetDeVoyage(): ?string
    {
        return $this->carnetDeVoyage;
    }

    public function setCarnetDeVoyage(?string $carnetDeVoyage): static
    {
        $this->carnetDeVoyage = $carnetDeVoyage;

        return $this;
    }

    public function getInventaire(): ?string
    {
        return $this->inventaire;
    }

    public function setInventaire(string $inventaire): static
    {
        $this->inventaire = $inventaire;

        return $this;
    }
}
