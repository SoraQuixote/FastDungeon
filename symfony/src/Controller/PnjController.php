<?php

namespace App\Controller;

use App\Entity\Pnj;
use App\Entity\Campagne; // <--- NE PAS OUBLIER CET IMPORT
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
        $campagne = null;

        // --- LOGIQUE DE LIAISON ---
        $campagneId = $request->query->get('campagne');
        
        if ($campagneId) {
            $campagne = $entityManager->getRepository(Campagne::class)->find($campagneId);
            if ($campagne) {
                // Liaison selon votre MCD (comprendre / pnj_campagne)
                $pnj->addCampagne($campagne);
            }
        }

        // On utilise ici le formulaire, mais dans votre Twig vous avez fait du HTML manuel.
        // Pour que les modales et les champs fonctionnent comme pour Personnage, 
        // assurez-vous que PnjType ne bloque pas les champs supplémentaires.
        $form = $this->createForm(PnjType::class, $pnj);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload d'image si nécessaire ici
            
            $entityManager->persist($pnj);
            $entityManager->flush();

            if ($campagneId) {
                // Redirection vers l'édition de la campagne (onglet PNJ)
                return $this->redirectToRoute('app_campagne_edit', ['id' => $campagneId]);
            }

            return $this->redirectToRoute('app_pnj_index');
        }

        // IMPORTANT : On passe 'campagne' et 'pnj' au template
        return $this->render('campagne/newpnj.html.twig', [
            'pnj' => $pnj,
            'campagne' => $campagne,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pnj_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pnj $pnj, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PnjType::class, $pnj);
        $form->handleRequest($request);

        // On récupère la campagne liée pour le bouton "Annuler" du template
        $campagne = $pnj->getCampagnes()->first(); 

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_campagne_edit', ['id' => $campagne->getId()]);
        }

        return $this->render('campagne/newpnj.html.twig', [
            'pnj' => $pnj,
            'campagne' => $campagne,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_pnj_show', methods: ['GET'])]
    public function show(Pnj $pnj): Response
    {
        return $this->render('pnj/show.html.twig', [
            'pnj' => $pnj,
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
