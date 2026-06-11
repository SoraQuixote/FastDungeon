<?php

namespace App\Entity;

use App\Repository\StatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatRepository::class)]
class Stat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $valeur = null;

    #[ORM\ManyToOne(targetEntity: Personnage::class, inversedBy: 'stats')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Personnage $personnage = null;

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getValeur(): ?int { return $this->valeur; }
    public function setValeur(int $valeur): static { $this->valeur = $valeur; return $this; }

    public function getPersonnage(): ?Personnage { return $this->personnage; }
    public function setPersonnage(?Personnage $personnage): static { $this->personnage = $personnage; return $this; }
}