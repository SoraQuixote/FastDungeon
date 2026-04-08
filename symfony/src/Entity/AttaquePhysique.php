<?php

namespace App\Entity;

use App\Repository\AttaquePhysiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttaquePhysiqueRepository::class)]
class AttaquePhysique extends Attaque
{
    #[ORM\Column(nullable: true)]
    private ?int $degatDeContre = null;

    public function getDegatDeContre(): ?int { return $this->degatDeContre; }
    public function setDegatDeContre(?int $degatDeContre): static { $this->degatDeContre = $degatDeContre; return $this; }
}