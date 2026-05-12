<?php

namespace App\Form;

use App\Entity\Personnage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PersonnageType extends AbstractType
{
    // Définit tous les champs du formulaire de personnage
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Identité
            ->add('nom')
            ->add('prenom')
            ->add('niveau')
            // Points de vie (max et actuel)
            ->add('pointDeVie')
            ->add('vieActuelle')
            // Statistiques principales
            ->add('stateForce')
            ->add('stateConstitution')
            ->add('stateRapidite')
            ->add('stateIntelligence')
            // Résistances
            ->add('resistancePhysique')
            ->add('resistanceMagique')
            ->add('resistanceMentale')
            // Champs texte libres
            ->add('carnetDeVoyage')
            ->add('histoire')
            ->add('inventaire')
            // Champ fichier pour l'image de portrait
            // mapped=false : géré manuellement dans le contrôleur, pas lié directement à l'entité
            ->add('portrait', FileType::class, [
                'label' => 'Portrait (Image)',
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    // Lie le formulaire à l'entité Personnage
    // allow_extra_fields permet d'ignorer les champs dynamiques ajoutés en JS (attaques, objets...)
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personnage::class,
            'allow_extra_fields' => true, 
        ]);
    }
}