<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    // Définit les champs du formulaire d'inscription
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pseudo avec placeholder d'exemple
            ->add('pseudo', null, [
                'label' => 'Votre Pseudo',
                'attr' => ['placeholder' => 'Ex: JeanDupont']
            ])
            // Case à cocher obligatoire pour accepter les CGU
            // mapped=false : non lié à l'entité, utilisé uniquement pour la validation
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(
                        message: 'You should agree to our terms.',
                    ),
                ],
            ])
            // Champ mot de passe en clair, traité et haché dans le contrôleur
            // mapped=false : non lié à l'entité User directement
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter a password',
                    ),
                    // Longueur minimale de 6 caractères, max 4096 (limite de sécurité Symfony)
                    new Length(
                        min: 6,
                        minMessage: 'Your password should be at least {{ limit }} characters',
                        max: 4096,
                    ),
                ],
            ])
        ;
    }

    // Lie le formulaire à l'entité User
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}