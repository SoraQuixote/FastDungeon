<?php

namespace App\Entity;

use App\Repository\AttaqueMagiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttaqueMagiqueRepository::class)]
class AttaqueMagique extends Attaque
{
    public function getDiscriminatorType(): string
    {
        return 'magique';
    }
}