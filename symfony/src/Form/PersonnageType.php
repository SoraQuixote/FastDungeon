<?php

    namespace App\Form;

    use App\Entity\Personnage;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Form\Extension\Core\Type\FileType;

    class PersonnageType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('nom')
                ->add('prenom')
                ->add('niveau')
                ->add('pointDeVie')
                ->add('vieActuelle')
                ->add('stateForce')
                ->add('stateConstitution')
                ->add('stateRapidite')
                ->add('stateIntelligence')
                ->add('resistancePhysique')
                ->add('resistanceMagique')
                ->add('resistanceMentale')
                ->add('carnetDeVoyage')
                ->add('histoire')
                ->add('inventaire')
                ->add('portrait', FileType::class, [
                    'label' => 'Portrait (Image)',
                    'mapped' => false, // On le garde en false pour gérer l'upload manuellement dans le contrôleur
                    'required' => false,
                ])
            ;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Personnage::class,
                // Option de secours si tu ajoutes des champs dynamiques en JS plus tard
                'allow_extra_fields' => true, 
            ]);
        }
    }
