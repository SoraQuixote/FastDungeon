<?php

// AdminController.php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\PersonnageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// Toutes les routes de ce contrôleur commencent par /admin
// et sont accessibles uniquement aux utilisateurs ayant le rôle ROLE_ADMIN
#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    // Page principale du tableau de bord admin
    // Récupère tous les utilisateurs et personnages pour les afficher
    #[Route('/', name: 'app_admin_dashboard')]
    public function index(UserRepository $userRepo, PersonnageRepository $persoRepo): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepo->findAll(),
            'personnages' => $persoRepo->findAll(),
        ]);
    }

    // Page listant tous les utilisateurs inscrits
    #[Route('/utilisateurs', name: 'app_admin_users')]
    public function listUsers(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // Ajoute ou retire le rôle Admin à un utilisateur selon son état actuel
    #[Route('/user/{id}/toggle-role', name: 'app_admin_user_toggle_role', methods: ['POST'])]
    public function toggleRole(User $user, \App\Repository\RoleRepository $roleRepo, EntityManagerInterface $em): Response
    {
        // Cherche le rôle ROLE_ADMIN en base de données
        $adminRole = $roleRepo->findOneBy(['libelle' => 'ROLE_ADMIN']);
        
        // Si l'utilisateur est déjà admin, on lui retire le rôle, sinon on le lui donne
        if ($user->getRoleEntities()->contains($adminRole)) {
            $user->removeRoleEntity($adminRole);
            $this->addFlash('success', $user->getPseudo() . " n'est plus Admin.");
        } else {
            $user->addRoleEntity($adminRole);
            $this->addFlash('success', $user->getPseudo() . " est maintenant Admin.");
        }

        // Sauvegarde le changement en base de données
        $em->flush();
        return $this->redirectToRoute('app_admin_users');
    }

    // Supprime définitivement un utilisateur
    #[Route('/user/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        // Empêche l'admin de se supprimer lui-même
        if ($user === $this->getUser()) {
            $this->addFlash('error', "Vous ne pouvez pas vous supprimer vous-même !");
            return $this->redirectToRoute('app_admin_users');
        }

        // Supprime l'utilisateur et sauvegarde
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', "L'utilisateur a été banni de la taverne.");
        return $this->redirectToRoute('app_admin_users');
    }
}