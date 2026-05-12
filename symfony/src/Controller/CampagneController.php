<?php

namespace App\Controller;

use App\Entity\Campagne;
use App\Form\CampagneType;
use App\Repository\CampagneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Toutes les routes commencent par /campagne
#[Route('/campagne')]
final class CampagneController extends AbstractController
{
    // Affiche la liste de toutes les campagnes
    #[Route(name: 'app_campagne_index', methods: ['GET'])]
    public function index(CampagneRepository $campagneRepository): Response
    {
        return $this->render('campagne/index.html.twig', [
            'campagnes' => $campagneRepository->findAll(),
        ]);
    }

    // Affiche et traite le formulaire de création d'une nouvelle campagne
    #[Route('/new', name: 'app_campagne_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campagne = new Campagne();
        $form = $this->createForm(CampagneType::class, $campagne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associe la campagne à l'utilisateur connecté
            $campagne->setUser($this->getUser());
            $entityManager->persist($campagne);
            $entityManager->flush();

            return $this->redirectToRoute('app_campagne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('campagne/new.html.twig', [
            'campagne' => $campagne,
            'form' => $form,
        ]);
    }

    // Affiche le détail d'une campagne
    #[Route('/{id}', name: 'app_campagne_show', methods: ['GET'])]
    public function show(Campagne $campagne): Response
    {
        return $this->render('campagne/show.html.twig', [
            'campagne' => $campagne,
        ]);
    }

    // Affiche et traite le formulaire de modification d'une campagne existante
    #[Route('/{id}/edit', name: 'app_campagne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Campagne $campagne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CampagneType::class, $campagne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde les modifications sans nouvel persist (entité déjà suivie par Doctrine)
            $entityManager->flush();

            return $this->redirectToRoute('app_campagne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('campagne/edit.html.twig', [
            'campagne' => $campagne,
            'form' => $form,
        ]);
    }

    // Supprime une campagne après vérification du token CSRF
    #[Route('/{id}', name: 'app_campagne_delete', methods: ['POST'])]
    public function delete(Request $request, Campagne $campagne, EntityManagerInterface $entityManager): Response
    {
        // Vérifie que la requête vient bien du formulaire de suppression (sécurité anti-CSRF)
        if ($this->isCsrfTokenValid('delete'.$campagne->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($campagne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_campagne_index', [], Response::HTTP_SEE_OTHER);
    }
}