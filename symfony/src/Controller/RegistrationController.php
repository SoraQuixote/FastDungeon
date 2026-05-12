<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    // Gère l'inscription d'un nouvel utilisateur
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        Security $security, 
        EntityManagerInterface $entityManager,
        \App\Repository\RoleRepository $roleRepository
        ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère le mot de passe en clair depuis le formulaire
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Hache le mot de passe avant de le stocker (ne jamais stocker en clair)
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Attribue automatiquement le rôle ROLE_USER à tout nouvel inscrit
            $defaultRole = $roleRepository->findOneBy(['libelle' => 'ROLE_USER']);
            if ($defaultRole) {
                $user->addRoleEntity($defaultRole);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            // Connecte l'utilisateur automatiquement après son inscription
            return $security->login($user, UserAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}