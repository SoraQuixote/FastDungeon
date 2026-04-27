<?php

namespace App\Form;

use App\Entity\Campagne;
use App\Entity\Personnage;
use App\Entity\Plateau;
use App\Entity\Pnj;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampagneType extends AbstractType
{
    // src/Form/CampagneType.php

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom')
        ->add('textPresentation', null, ['label' => 'Présentation publique'])
        ->add('scenario', null, ['label' => 'Scénario (Secret MJ)'])
        ->add('carteMonde', null, ['label' => 'URL de la Carte du Monde']) // Ajouté
        ->add('etat', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
            'choices' => [
                'En préparation' => 'En préparation',
                'En cours' => 'En cours',
                'Terminée' => 'Terminée',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Campagne::class,
        ]);
    }
}
