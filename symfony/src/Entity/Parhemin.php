<?php

namespace App\Entity;

use App\Repository\ParheminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParheminRepository::class)]
class Parhemin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?float $degat = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $effet = null;

    #[ORM\Column(length: 50)]
    private ?string $rarete = null;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDegat(): ?float
    {
        return $this->degat;
    }

    public function setDegat(?float $degat): static
    {
        $this->degat = $degat;

        return $this;
    }

    public function getEffet(): ?string
    {
        return $this->effet;
    }

    public function setEffet(?string $effet): static
    {
        $this->effet = $effet;

        return $this;
    }

    public function getRarete(): ?string
    {
        return $this->rarete;
    }

    public function setRarete(string $rarete): static
    {
        $this->rarete = $rarete;

        return $this;
    }
}
