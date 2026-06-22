<?php

namespace App\Entity;

use App\Repository\PassifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PassifRepository::class)]
class Passif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $effet = null;

    #[ORM\Column(length: 20)]
    private ?string $categorie = null; // 'passif' ou 'maitrise'

    #[ORM\ManyToMany(targetEntity: Personnage::class, mappedBy: 'passifs')]
    private Collection $personnages;

    #[ORM\ManyToMany(targetEntity: Pnj::class, mappedBy: 'passifs')]
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

    public function getEffet(): ?string { return $this->effet; }
    public function setEffet(?string $effet): static { $this->effet = $effet; return $this; }

    public function getCategorie(): ?string { return $this->categorie; }
    public function setCategorie(string $categorie): static { $this->categorie = $categorie; return $this; }

    public function getPersonnages(): Collection { return $this->personnages; }
    public function getPnjs(): Collection { return $this->pnjs; }

    public function addPersonnage(Personnage $personnage): static
    {
        if (!$this->personnages->contains($personnage)) {
            $this->personnages->add($personnage);
        }
        return $this;
    }
}