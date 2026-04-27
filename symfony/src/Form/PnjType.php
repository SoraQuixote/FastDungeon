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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('type')
            ->add('description')
            ->add('histoire')
            ->add('inventaire')
            ->add('armure', EntityType::class, [
                'class' => Armure::class,
                'choice_label' => 'id',
            ])
            ->add('arme', EntityType::class, [
                'class' => Arme::class,
                'choice_label' => 'id',
            ])
            ->add('objets', EntityType::class, [
                'class' => Objet::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('attaques', EntityType::class, [
                'class' => Attaque::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('campagnes', EntityType::class, [
                'class' => Campagne::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pnj::class,
        ]);
    }
}
