<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // La page d'accueil redirige directement vers la page de connexion
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    // Affiche le formulaire de connexion
    // En cas d'erreur, renvoie le message d'erreur et le dernier pseudo saisi
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère l'éventuelle erreur de connexion (mauvais mot de passe, etc.)
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier identifiant saisi pour le pré-remplir dans le formulaire
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    // La déconnexion est interceptée par le pare-feu Symfony, cette méthode ne s'exécute jamais
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}