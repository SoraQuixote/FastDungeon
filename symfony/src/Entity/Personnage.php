<?php

namespace App\Entity;

use App\Repository\PersonnageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonnageRepository::class)]
class Personnage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $personnageUser = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $carnetDeVoyage = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $inventaire = null;

    #[ORM\Column]
    private ?int $pointDeVie = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $histoire = null;

    #[ORM\Column]
    private ?int $niveau = null;

    #[ORM\Column(nullable: true)]
    private ?int $vieActuelle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $portrait = null;

    // ── Relations ──────────────────────────────────────────

    /**
     * Relation "posseder" : un Personnage appartient à un User (1,1)
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'personnages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * Relation "porter" : un Personnage porte 0 ou 1 Armure (0,1)
     */
    #[ORM\ManyToOne(targetEntity: Armure::class, inversedBy: 'personnages')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Armure $armure = null;

    /**
     * Relation "avoir3" : un Personnage possède 0 ou 1 Arme (0,1)
     */
    #[ORM\ManyToOne(targetEntity: Arme::class, inversedBy: 'personnages')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Arme $arme = null;

    /**
     * Relation "detenir" : un Personnage peut détenir plusieurs Objets (0,n)
     */
    #[ORM\ManyToMany(targetEntity: Objet::class, inversedBy: 'personnages')]
    #[ORM\JoinTable(name: 'personnage_objet')]
    private Collection $objets;

    /**
     * Relation "avoir" : un Personnage peut avoir plusieurs Attaques (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Attaque::class, inversedBy: 'personnages')]
    #[ORM\JoinTable(name: 'personnage_attaque')]
    private Collection $attaques;

    /**
     * Relation "associer" : un Personnage peut être associé à plusieurs Campagnes (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Campagne::class, inversedBy: 'personnages')]
    #[ORM\JoinTable(name: 'personnage_campagne')]
    private Collection $campagnes;

    #[ORM\OneToMany(targetEntity: Stat::class, mappedBy: 'personnage', cascade: ['persist', 'remove'])]
    private Collection $stats;

    public function __construct()
    {
        $this->objets = new ArrayCollection();
        $this->attaques = new ArrayCollection();
        $this->campagnes = new ArrayCollection();
        $this->stats = new ArrayCollection();
    }

    // ── Getters / Setters ──────────────────────────────────

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(?string $nom): static { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(?string $prenom): static { $this->prenom = $prenom; return $this; }

    public function getPersonnageUser(): ?string { return $this->personnageUser; }
    public function setPersonnageUser(?string $personnageUser): static { $this->personnageUser = $personnageUser; return $this; }

    public function getCarnetDeVoyage(): ?string { return $this->carnetDeVoyage; }
    public function setCarnetDeVoyage(?string $carnetDeVoyage): static { $this->carnetDeVoyage = $carnetDeVoyage; return $this; }

    public function getInventaire(): ?string { return $this->inventaire; }
    public function setInventaire(?string $inventaire): static { $this->inventaire = $inventaire; return $this; }

    public function getPointDeVie(): ?int { return $this->pointDeVie; }
    public function setPointDeVie(?int $pointDeVie): static { $this->pointDeVie = $pointDeVie; return $this; }

    public function getVieActuelle(): ?int { return $this->vieActuelle; }
    public function setVieActuelle(?int $vieActuelle): static { $this->vieActuelle = $vieActuelle; return $this; }

    public function getHistoire(): ?string { return $this->histoire; }
    public function setHistoire(?string $histoire): static { $this->histoire = $histoire; return $this; }

    public function getNiveau(): ?int { return $this->niveau; }
    public function setNiveau(?int $niveau): static { $this->niveau = $niveau; return $this; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }

    public function getArmure(): ?Armure { return $this->armure; }
    public function setArmure(?Armure $armure): static { $this->armure = $armure; return $this; }

    public function getArme(): ?Arme { return $this->arme; }
    public function setArme(?Arme $arme): static { $this->arme = $arme; return $this; }

    public function getObjets(): Collection { return $this->objets; }
    public function addObjet(Objet $objet): static
    {
        if (!$this->objets->contains($objet)) { $this->objets->add($objet); }
        return $this;
    }
    public function removeObjet(Objet $objet): static { $this->objets->removeElement($objet); return $this; }

    public function getAttaques(): Collection { return $this->attaques; }
    public function addAttaque(Attaque $attaque): static
    {
        if (!$this->attaques->contains($attaque)) {
            $this->attaques->add($attaque);
            $attaque->addPersonnage($this); // ← indispensable pour le ManyToMany
        }
        return $this;
    }
    public function removeAttaque(Attaque $attaque): static { $this->attaques->removeElement($attaque); return $this; }

    public function getCampagnes(): Collection { return $this->campagnes; }
    public function addCampagne(Campagne $campagne): static
    {
        if (!$this->campagnes->contains($campagne)) {
            $this->campagnes->add($campagne);
            $campagne->addPersonnage($this);
        }
        return $this;
    }
    public function removeCampagne(Campagne $campagne): static
    {
        if ($this->campagnes->removeElement($campagne)) {
            $campagne->removePersonnage($this);
        }
        return $this;
    }

   

    public function getPortrait(): ?string
    {
        return $this->portrait;
    }

    public function setPortrait(?string $portrait): static
    {
        $this->portrait = $portrait;
        return $this;
    }

    public function getStats(): Collection { return $this->stats; }

    public function addStat(Stat $stat): static
    {
        if (!$this->stats->contains($stat)) {
            $this->stats->add($stat);
            $stat->setPersonnage($this);
        }
        return $this;
    }

    public function removeStat(Stat $stat): static
    {
        $this->stats->removeElement($stat);
        return $this;
    }
}