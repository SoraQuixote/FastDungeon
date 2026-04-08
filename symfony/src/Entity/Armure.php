<?php

namespace App\Entity;

use App\Repository\ArmureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArmureRepository::class)]
class Armure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $bonus = null;

    /**
     * Relation "porter" inverse : une Armure peut être portée par plusieurs Personnages (0,n)
     */
    #[ORM\OneToMany(targetEntity: Personnage::class, mappedBy: 'armure')]
    private Collection $personnages;

    /**
     * Relation "porter2" inverse : une Armure peut être portée par plusieurs Pnj (0,n)
     */
    #[ORM\OneToMany(targetEntity: Pnj::class, mappedBy: 'armure')]
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

    public function getBonus(): ?int { return $this->bonus; }
    public function setBonus(int $bonus): static { $this->bonus = $bonus; return $this; }

    public function getPersonnages(): Collection { return $this->personnages; }
    public function addPersonnage(Personnage $personnage): static
    {
        if (!$this->personnages->contains($personnage)) {
            $this->personnages->add($personnage);
            $personnage->setArmure($this);
        }
        return $this;
    }
    public function removePersonnage(Personnage $personnage): static
    {
        if ($this->personnages->removeElement($personnage)) {
            if ($personnage->getArmure() === $this) { $personnage->setArmure(null); }
        }
        return $this;
    }

    public function getPnjs(): Collection { return $this->pnjs; }
    public function addPnj(Pnj $pnj): static
    {
        if (!$this->pnjs->contains($pnj)) {
            $this->pnjs->add($pnj);
            $pnj->setArmure($this);
        }
        return $this;
    }
    public function removePnj(Pnj $pnj): static
    {
        if ($this->pnjs->removeElement($pnj)) {
            if ($pnj->getArmure() === $this) { $pnj->setArmure(null); }
        }
        return $this;
    }
}