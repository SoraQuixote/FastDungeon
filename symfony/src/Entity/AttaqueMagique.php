<?php

namespace App\Entity;

use App\Repository\AttaqueMagiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttaqueMagiqueRepository::class)]
class AttaqueMagique extends Attaque
{
    #[ORM\Column(length: 30, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $ptsDeVie = null;

    /**
     * Relation "concerne" : une AttaqueMagique est liée à un TypeMagique (1,1)
     * Un TypeMagique peut concerner plusieurs AttaqueMagique (0,n)
     */
    #[ORM\ManyToOne(targetEntity: TypeMagique::class, inversedBy: 'attaquesMagiques')]
    #[ORM\JoinColumn(nullable: true)]
    private ?TypeMagique $typeMagique = null;

    public function getType(): ?string { return $this->type; }
    public function setType(?string $type): static { $this->type = $type; return $this; }

    public function getPtsDeVie(): ?int { return $this->ptsDeVie; }
    public function setPtsDeVie(?int $ptsDeVie): static { $this->ptsDeVie = $ptsDeVie; return $this; }

    public function getTypeMagique(): ?TypeMagique { return $this->typeMagique; }
    public function setTypeMagique(?TypeMagique $typeMagique): static { $this->typeMagique = $typeMagique; return $this; }
}