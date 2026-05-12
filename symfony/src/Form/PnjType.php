<?php

namespace App\Form;

use App\Entity\Arme;
use App\Entity\Armure;
use App\Entity\Attaque;
use App\Entity\Campagne;
use App\Entity\Objet;
use App\Entity\Pnj;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PnjType extends AbstractType
{
    // Définit les champs du formulaire de PNJ
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Identité et description
            ->add('nom')
            ->add('prenom')
            ->add('type')
            ->add('description')
            ->add('histoire')
            ->add('inventaire')
            // Sélection d'une armure depuis les entités existantes en base
            ->add('armure', EntityType::class, [
                'class' => Armure::class,
                'choice_label' => 'id',
            ])
            // Sélection d'une arme depuis les entités existantes en base
            ->add('arme', EntityType::class, [
                'class' => Arme::class,
                'choice_label' => 'id',
            ])
            // Sélection multiple d'objets
            ->add('objets', EntityType::class, [
                'class' => Objet::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            // Sélection multiple d'attaques
            ->add('attaques', EntityType::class, [
                'class' => Attaque::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            // Sélection multiple des campagnes auxquelles appartient ce PNJ
            ->add('campagnes', EntityType::class, [
                'class' => Campagne::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    // Lie le formulaire à l'entité Pnj
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pnj::class,
        ]);
    }
}