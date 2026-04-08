<?php

namespace App\Entity;

use App\Repository\PnjRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PnjRepository::class)]
class Pnj
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $histoire = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $inventaire = null;

    // ── Relations ──────────────────────────────────────────

    /**
     * Relation "porter2" : un Pnj porte 0 ou 1 Armure (0,1)
     */
    #[ORM\ManyToOne(targetEntity: Armure::class, inversedBy: 'pnjs')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Armure $armure = null;

    /**
     * Relation "avoir5" : un Pnj possède 0 ou 1 Arme (0,1)
     */
    #[ORM\ManyToOne(targetEntity: Arme::class, inversedBy: 'pnjs')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Arme $arme = null;

    /**
     * Relation "detenir2" : un Pnj peut détenir plusieurs Objets (0,n)
     */
    #[ORM\ManyToMany(targetEntity: Objet::class, inversedBy: 'pnjs')]
    #[ORM\JoinTable(name: 'pnj_objet')]
    private Collection $objets;

    /**
     * Relation "Avoir6" : un Pnj peut avoir plusieurs Attaques (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Attaque::class, inversedBy: 'pnjs')]
    #[ORM\JoinTable(name: 'pnj_attaque')]
    private Collection $attaques;

    /**
     * Relation "comprendre" : un Pnj peut appartenir à plusieurs Campagnes (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Campagne::class, inversedBy: 'pnjs')]
    #[ORM\JoinTable(name: 'pnj_campagne')]
    private Collection $campagnes;

    public function __construct()
    {
        $this->objets = new ArrayCollection();
        $this->attaques = new ArrayCollection();
        $this->campagnes = new ArrayCollection();
    }

    // ── Getters / Setters ──────────────────────────────────

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(string $prenom): static { $this->prenom = $prenom; return $this; }

    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }

    public function getHistoire(): ?string { return $this->histoire; }
    public function setHistoire(?string $histoire): static { $this->histoire = $histoire; return $this; }

    public function getInventaire(): ?string { return $this->inventaire; }
    public function setInventaire(?string $inventaire): static { $this->inventaire = $inventaire; return $this; }

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
        if (!$this->attaques->contains($attaque)) { $this->attaques->add($attaque); }
        return $this;
    }
    public function removeAttaque(Attaque $attaque): static { $this->attaques->removeElement($attaque); return $this; }

    public function getCampagnes(): Collection { return $this->campagnes; }
    public function addCampagne(Campagne $campagne): static
    {
        if (!$this->campagnes->contains($campagne)) {
            $this->campagnes->add($campagne);
            $campagne->addPnj($this);
        }
        return $this;
    }
    public function removeCampagne(Campagne $campagne): static
    {
        if ($this->campagnes->removeElement($campagne)) {
            $campagne->removePnj($this);
        }
        return $this;
    }
}