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

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function index(UserRepository $userRepo, PersonnageRepository $persoRepo): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepo->findAll(),
            'personnages' => $persoRepo->findAll(),
        ]);
    }

    #[Route('/utilisateurs', name: 'app_admin_users')]
    public function listUsers(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // J'ai enlevé le "/admin" au début de la route car il est déjà dans le préfixe de la classe
    #[Route('/user/{id}/toggle-role', name: 'app_admin_user_toggle_role', methods: ['POST'])]
    public function toggleRole(User $user, \App\Repository\RoleRepository $roleRepo, EntityManagerInterface $em): Response
    {
        $adminRole = $roleRepo->findOneBy(['libelle' => 'ROLE_ADMIN']);
        
        if ($user->getRoleEntities()->contains($adminRole)) {
            $user->removeRoleEntity($adminRole);
            $this->addFlash('success', $user->getPseudo() . " n'est plus Admin.");
        } else {
            $user->addRoleEntity($adminRole);
            $this->addFlash('success', $user->getPseudo() . " est maintenant Admin.");
        }

        $em->flush();
        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/user/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        if ($user === $this->getUser()) {
            $this->addFlash('error', "Vous ne pouvez pas vous supprimer vous-même !");
            return $this->redirectToRoute('app_admin_users');
        }

        $em->remove($user);
        $em->flush();

        $this->addFlash('success', "L'utilisateur a été banni de la taverne.");
        return $this->redirectToRoute('app_admin_users');
    }
}
