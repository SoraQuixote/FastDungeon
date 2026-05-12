<?php

namespace App\Controller;

use App\Entity\Pnj;
use App\Entity\Campagne;
use App\Form\PnjType;
use App\Repository\PnjRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Toutes les routes commencent par /pnj
#[Route('/pnj')]
final class PnjController extends AbstractController
{
    // Affiche la liste de tous les PNJ
    #[Route(name: 'app_pnj_index', methods: ['GET'])]
    public function index(PnjRepository $pnjRepository): Response
    {
        return $this->render('pnj/index.html.twig', [
            'pnjs' => $pnjRepository->findAll(),
        ]);
    }

    // Affiche et traite le formulaire de création d'un PNJ
    // Peut recevoir un paramètre ?campagne=X pour lier le PNJ directement à une campagne
    #[Route('/new', name: 'app_pnj_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pnj = new Pnj();
        $campagne = null;

        // Récupère l'ID de la campagne depuis l'URL si présent
        $campagneId = $request->query->get('campagne');
        
        if ($campagneId) {
            $campagne = $entityManager->getRepository(Campagne::class)->find($campagneId);
            if ($campagne) {
                // Lie directement le PNJ à la campagne trouvée
                $pnj->addCampagne($campagne);
            }
        }

        $form = $this->createForm(PnjType::class, $pnj);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pnj);
            $entityManager->flush();

            // Redirige vers la campagne d'origine si le PNJ lui est lié
            if ($campagneId) {
                return $this->redirectToRoute('app_campagne_edit', ['id' => $campagneId]);
            }

            return $this->redirectToRoute('app_pnj_index');
        }

        return $this->render('campagne/newpnj.html.twig', [
            'pnj' => $pnj,
            'campagne' => $campagne,
            'form' => $form->createView(),
        ]);
    }

    // Affiche et traite le formulaire de modification d'un PNJ existant
    #[Route('/{id}/edit', name: 'app_pnj_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pnj $pnj, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PnjType::class, $pnj);
        $form->handleRequest($request);

        // Récupère la première campagne liée pour pouvoir rediriger vers elle
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

    // Affiche le détail d'un PNJ
    #[Route('/{id}', name: 'app_pnj_show', methods: ['GET'])]
    public function show(Pnj $pnj): Response
    {
        return $this->render('pnj/show.html.twig', [
            'pnj' => $pnj,
        ]);
    }        

    // Supprime un PNJ après vérification du token CSRF
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