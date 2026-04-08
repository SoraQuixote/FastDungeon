<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['pseudo'], message: 'There is already an account with this pseudo')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * Relation "posseder" inverse : un User peut avoir plusieurs Personnages (0,n)
     */
    #[ORM\OneToMany(targetEntity: Personnage::class, mappedBy: 'user')]
    private Collection $personnages;

    /**
     * Relation "créer" inverse : un User peut créer plusieurs Campagnes (0,n)
     */
    #[ORM\OneToMany(targetEntity: Campagne::class, mappedBy: 'user')]
    private Collection $campagnes;

    /**
     * Relation "attribuer" : un User peut avoir plusieurs Roles (ManyToMany)
     */
    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'user_role')]
    private Collection $roleEntities;

    public function __construct()
    {
        $this->personnages = new ArrayCollection();
        $this->campagnes = new ArrayCollection();
        $this->roleEntities = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getUserIdentifier(): string { return $this->pseudo; }

    public function getPseudo(): ?string { return $this->pseudo; }
    public function setPseudo(string $pseudo): self { $this->pseudo = $pseudo; return $this; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }

    /**
     * Retourne les rôles pour Symfony Security.
     * Garantit ROLE_USER par défaut si aucun rôle assigné.
     */
    public function getRoles(): array
    {
        $roles = $this->roleEntities->map(fn(Role $role) => $role->getLibelle())->toArray();
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

    public function eraseCredentials(): void {}

    public function getPersonnages(): Collection { return $this->personnages; }
    public function addPersonnage(Personnage $personnage): static
    {
        if (!$this->personnages->contains($personnage)) {
            $this->personnages->add($personnage);
            $personnage->setUser($this);
        }
        return $this;
    }
    public function removePersonnage(Personnage $personnage): static
    {
        if ($this->personnages->removeElement($personnage)) {
            if ($personnage->getUser() === $this) { $personnage->setUser(null); }
        }
        return $this;
    }

    public function getCampagnes(): Collection { return $this->campagnes; }
    public function addCampagne(Campagne $campagne): static
    {
        if (!$this->campagnes->contains($campagne)) {
            $this->campagnes->add($campagne);
            $campagne->setUser($this);
        }
        return $this;
    }
    public function removeCampagne(Campagne $campagne): static
    {
        if ($this->campagnes->removeElement($campagne)) {
            if ($campagne->getUser() === $this) { $campagne->setUser(null); }
        }
        return $this;
    }

    public function getRoleEntities(): Collection { return $this->roleEntities; }
    public function addRoleEntity(Role $role): static
    {
        if (!$this->roleEntities->contains($role)) { $this->roleEntities->add($role); }
        return $this;
    }
    public function removeRoleEntity(Role $role): static
    {
        $this->roleEntities->removeElement($role);
        return $this;
    }
}