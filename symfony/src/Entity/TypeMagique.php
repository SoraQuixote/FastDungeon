<?php

namespace App\Entity;

use App\Repository\TypeMagiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeMagiqueRepository::class)]
class TypeMagique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    /**
     * Relation "concerne" inverse : un TypeMagique peut concerner plusieurs AttaqueMagique (0,n)
     */
    #[ORM\OneToMany(targetEntity: AttaqueMagique::class, mappedBy: 'typeMagique')]
    private Collection $attaquesMagiques;

    public function __construct()
    {
        $this->attaquesMagiques = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getType(): ?string { return $this->type; }
    public function setType(?string $type): static { $this->type = $type; return $this; }

    public function getAttaquesMagiques(): Collection { return $this->attaquesMagiques; }

    public function addAttaqueMagique(AttaqueMagique $attaque): static
    {
        if (!$this->attaquesMagiques->contains($attaque)) {
            $this->attaquesMagiques->add($attaque);
            $attaque->setTypeMagique($this);
        }
        return $this;
    }

    public function removeAttaqueMagique(AttaqueMagique $attaque): static
    {
        if ($this->attaquesMagiques->removeElement($attaque)) {
            if ($attaque->getTypeMagique() === $this) {
                $attaque->setTypeMagique(null);
            }
        }
        return $this;
    }
}