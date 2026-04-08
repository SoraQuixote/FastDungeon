<?php

namespace App\Entity;

use App\Repository\PlateauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlateauRepository::class)]
class Plateau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tuile = null;

    /**
     * Relation "Appartenir" inverse : un Plateau peut appartenir à plusieurs Campagnes (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Campagne::class, mappedBy: 'plateaux')]
    private Collection $campagnes;

    public function __construct()
    {
        $this->campagnes = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getTuile(): ?string { return $this->tuile; }
    public function setTuile(?string $tuile): static { $this->tuile = $tuile; return $this; }

    public function getCampagnes(): Collection { return $this->campagnes; }
    public function addCampagne(Campagne $campagne): static
    {
        if (!$this->campagnes->contains($campagne)) {
            $this->campagnes->add($campagne);
            $campagne->addPlateau($this);
        }
        return $this;
    }
    public function removeCampagne(Campagne $campagne): static
    {
        if ($this->campagnes->removeElement($campagne)) {
            $campagne->removePlateau($this);
        }
        return $this;
    }
}