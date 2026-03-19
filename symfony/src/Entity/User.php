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
     * @var Collection<int, Personnage>
     */
    #[ORM\OneToMany(targetEntity: Personnage::class, mappedBy: 'user')]
    private Collection $personnages;

    public function __construct()
    {
        $this->personnages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->pseudo;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

        public function eraseCredentials(): void
    {
        // Vide les données sensibles temporaires si besoin
    }

        /**
         * @return Collection<int, Personnage>
         */
        public function getPersonnages(): Collection
        {
            return $this->personnages;
        }

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
                // set the owning side to null (unless already changed)
                if ($personnage->getUser() === $this) {
                    $personnage->setUser(null);
                }
            }

            return $this;
        }
}