<?php

namespace App\Entity;

use App\Repository\AttaquePhysiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttaquePhysiqueRepository::class)]
class AttaquePhysique extends Attaque
{
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $degatDeContre = null;

    public function getDegatDeContre(): ?string { return $this->degatDeContre; }
    public function setDegatDeContre(?string $degatDeContre): static { $this->degatDeContre = $degatDeContre; return $this; }

    public function getDiscriminatorType(): string
    {
        return 'physique';
    }
}