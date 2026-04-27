<?php

namespace App\Controller;

use App\Entity\Pnj;
use App\Form\PnjType;
use App\Repository\PnjRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pnj')]
final class PnjController extends AbstractController
{
    #[Route(name: 'app_pnj_index', methods: ['GET'])]
    public function index(PnjRepository $pnjRepository): Response
    {
        return $this->render('pnj/index.html.twig', [
            'pnjs' => $pnjRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pnj_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pnj = new Pnj();

        // --- LOGIQUE DE LIAISON ---
        // On regarde si "campagne" est présent dans l'URL (ex: /pnj/new?campagne=5)
        $campagneId = $request->query->get('campagne');
        
        if ($campagneId) {
            $campagne = $entityManager->getRepository(Campagne::class)->find($campagneId);
            if ($campagne) {
                // On ajoute la campagne au PNJ (relation ManyToMany)
                $pnj->addCampagne($campagne);
            }
        }
        // --------------------------

        $form = $this->createForm(PnjType::class, $pnj);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pnj);
            $entityManager->flush();

            // Une fois créé, on redirige vers l'édition de la campagne, onglet PNJ
            if ($campagneId) {
                return $this->redirectToRoute('app_campagne_edit', ['id' => $campagneId]);
            }

            return $this->redirectToRoute('app_pnj_index');
        }

        return $this->render('pnj/new.html.twig', [
            'pnj' => $pnj,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pnj_show', methods: ['GET'])]
    public function show(Pnj $pnj): Response
    {
        return $this->render('pnj/show.html.twig', [
            'pnj' => $pnj,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pnj_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pnj $pnj, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PnjType::class, $pnj);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pnj_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pnj/edit.html.twig', [
            'pnj' => $pnj,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pnj_delete', methods: ['POST'])]
    public function delete(Request $request, Pnj $pnj, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pnj->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pnj);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pnj_index', [], Response::HTTP_SEE_OTHER);
    }
}
