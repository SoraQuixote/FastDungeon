<?php

namespace App\Entity;

use App\Repository\AttaqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttaqueRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'dtype', type: 'string')]
#[ORM\DiscriminatorMap([
    'physique' => AttaquePhysique::class,
    'magique'  => AttaqueMagique::class,
])]
abstract class Attaque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $ptsDegat = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $effet = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $portee = null;

    /**
     * Relation "avoir" : une Attaque peut être utilisée par plusieurs Personnages (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Personnage::class, mappedBy: 'attaques')]
    private Collection $personnages;

    /**
     * Relation "Avoir6" : une Attaque peut être utilisée par plusieurs Pnj (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Pnj::class, mappedBy: 'attaques')]
    private Collection $pnjs;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $cout = null;

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

    public function getPtsDegat(): ?string { return $this->ptsDegat; }
    public function setPtsDegat(?string $ptsDegat): static { $this->ptsDegat = $ptsDegat; return $this; }

    public function getEffet(): ?string { return $this->effet; }
    public function setEffet(?string $effet): static { $this->effet = $effet; return $this; }

    public function getPortee(): ?string { return $this->portee; }
    public function setPortee(?string $portee): static { $this->portee = $portee; return $this; }

    public function getPersonnages(): Collection { return $this->personnages; }
    public function getPnjs(): Collection { return $this->pnjs; }

    public function addPersonnage(Personnage $personnage): static
    {
        if (!$this->personnages->contains($personnage)) {
            $this->personnages->add($personnage);
        }
        return $this;
    }

    public function getDiscriminatorType(): string
    {
        return 'physique'; // valeur par défaut
    }

   public function getCout(): ?string { return $this->cout; }
   public function setCout(?string $cout): static { $this->cout = $cout; return $this; }
}