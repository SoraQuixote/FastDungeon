<?php

namespace App\Entity;

use App\Repository\CampagneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampagneRepository::class)]
class Campagne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $scenario = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $carteMonde = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $textPresentation = null;

    #[ORM\Column(length: 50)]
    private ?string $etat = null;

    // ── Relations ──────────────────────────────────────────

    /**
     * Relation "créer" : une Campagne est créée par un User (ManyToOne)
     * Un User peut créer plusieurs Campagnes (0,n)
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'campagnes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * Relation "associer" inverse : une Campagne peut avoir plusieurs Personnages (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Personnage::class, mappedBy: 'campagnes')]
    private Collection $personnages;

    /**
     * Relation "comprendre" inverse : une Campagne peut avoir plusieurs Pnj (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Pnj::class, mappedBy: 'campagnes')]
    private Collection $pnjs;

    /**
     * Relation "Appartenir" : une Campagne peut avoir plusieurs Plateaux (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Plateau::class, inversedBy: 'campagnes')]
    #[ORM\JoinTable(name: 'campagne_plateau')]
    private Collection $plateaux;

    public function __construct()
    {
        $this->personnages = new ArrayCollection();
        $this->pnjs = new ArrayCollection();
        $this->plateaux = new ArrayCollection();
    }

    // ── Getters / Setters ──────────────────────────────────

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getScenario(): ?string { return $this->scenario; }
    public function setScenario(?string $scenario): static { $this->scenario = $scenario; return $this; }

    public function getCarteMonde(): ?string { return $this->carteMonde; }
    public function setCarteMonde(?string $carteMonde): static { $this->carteMonde = $carteMonde; return $this; }

    public function getTextPresentation(): ?string { return $this->textPresentation; }
    public function setTextPresentation(?string $textPresentation): static { $this->textPresentation = $textPresentation; return $this; }

    public function getEtat(): ?string { return $this->etat; }
    public function setEtat(string $etat): static { $this->etat = $etat; return $this; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }

    public function getPersonnages(): Collection { return $this->personnages; }
    public function addPersonnage(Personnage $personnage): static
    {
        if (!$this->personnages->contains($personnage)) { $this->personnages->add($personnage); }
        return $this;
    }
    public function removePersonnage(Personnage $personnage): static { $this->personnages->removeElement($personnage); return $this; }

    public function getPnjs(): Collection { return $this->pnjs; }
    public function addPnj(Pnj $pnj): static
    {
        if (!$this->pnjs->contains($pnj)) { $this->pnjs->add($pnj); }
        return $this;
    }
    public function removePnj(Pnj $pnj): static { $this->pnjs->removeElement($pnj); return $this; }

    public function getPlateaux(): Collection { return $this->plateaux; }
    public function addPlateau(Plateau $plateau): static
    {
        if (!$this->plateaux->contains($plateau)) {
            $this->plateaux->add($plateau);
            $plateau->addCampagne($this);
        }
        return $this;
    }
    public function removePlateau(Plateau $plateau): static
    {
        if ($this->plateaux->removeElement($plateau)) {
            $plateau->removeCampagne($this);
        }
        return $this;
    }
}