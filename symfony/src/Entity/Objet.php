<?php

namespace App\Entity;

use App\Repository\ObjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjetRepository::class)]
class Objet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $ptsDegat = null;

    #[ORM\Column(nullable: true)]
    private ?int $ptsDeVie = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $effet = null;

    /**
     * Relation "detenir" inverse : un Objet peut être détenu par plusieurs Personnages (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Personnage::class, mappedBy: 'objets')]
    private Collection $personnages;

    /**
     * Relation "detenir2" inverse : un Objet peut être détenu par plusieurs Pnj (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Pnj::class, mappedBy: 'objets')]
    private Collection $pnjs;

    public function __construct()
    {
        $this->personnages = new ArrayCollection();
        $this->pnjs = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }

    public function getPtsDegat(): ?int { return $this->ptsDegat; }
    public function setPtsDegat(?int $ptsDegat): static { $this->ptsDegat = $ptsDegat; return $this; }

    public function getPtsDeVie(): ?int { return $this->ptsDeVie; }
    public function setPtsDeVie(?int $ptsDeVie): static { $this->ptsDeVie = $ptsDeVie; return $this; }

    public function getEffet(): ?string { return $this->effet; }
    public function setEffet(?string $effet): static { $this->effet = $effet; return $this; }

    public function getPersonnages(): Collection { return $this->personnages; }
    public function getPnjs(): Collection { return $this->pnjs; }
}