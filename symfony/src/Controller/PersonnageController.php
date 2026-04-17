<?php

namespace App\Controller;

use App\Entity\Personnage;
use App\Form\PersonnageType;
use App\Repository\PersonnageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/personnage')]
final class PersonnageController extends AbstractController
{
    #[Route(name: 'app_personnage_index', methods: ['GET'])]
    public function index(PersonnageRepository $personnageRepository): Response
    {
        return $this->render('personnage/index.html.twig', [
            'personnages' => $personnageRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_personnage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $personnage = new Personnage();
        $personnage->setUser($this->getUser());
        $form = $this->createForm(PersonnageType::class, $personnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $portraitFile */
            $portraitFile = $form->get('portrait')->getData();

            if ($portraitFile) {
                $newFilename = uniqid().'.'.$portraitFile->guessExtension();
                try {
                    $portraitFile->move(
                        $this->getParameter('portraits_directory'),
                        $newFilename
                    );
                    $personnage->setPortrait($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload du portrait.');
                }
            }

            $entityManager->persist($personnage);
            $entityManager->flush();

            return $this->redirectToRoute('app_personnage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('personnage/new.html.twig', [
            'personnage' => $personnage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personnage_show', methods: ['GET'])]
    public function show(Personnage $personnage): Response
    {
        return $this->render('personnage/show.html.twig', [
            'personnage' => $personnage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_personnage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Personnage $personnage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonnageType::class, $personnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
                    // --- SAUVEGARDE DES ATTAQUES ---
            $dataPersonnage = $request->request->all('personnage');
            $attaquesData = $dataPersonnage['attaques'] ?? [];

            // On vide les anciennes attaques pour éviter les doublons
            foreach ($personnage->getAttaques() as $oldAtk) {
                $entityManager->remove($oldAtk);
            }

            foreach ($attaquesData as $data) {
                if (empty($data['nom'])) continue;

                // Normalisation pour éviter les problèmes de casse/espaces
                $type = strtolower(trim($data['type'] ?? ''));

                if ($type === 'magique') {
                    $atk = new \App\Entity\AttaqueMagique();
                    $atk->setPtsDeVie((int)($data['ptsDeVie'] ?? 0));
                    $atk->setType($data['magieType'] ?? 'Magie Noire');
                } else {
                    $atk = new \App\Entity\AttaquePhysique();
                    $atk->setDegatDeContre((int)($data['contre'] ?? 0));
                }

                $atk->setNom($data['nom']);
                $atk->setPortee($data['portee'] ?? 'Contact');
                $atk->setEffet($data['effet'] ?? '');
                $atk->setDescription($data['desc'] ?? '');
                $atk->setPtsDegat((int)($data['degat'] ?? 0));
                $atk->setCout((int)($data['cout'] ?? 0));

                $personnage->addAttaque($atk);
                $entityManager->persist($atk);
            }

            // --- 2. GESTION DU PORTRAIT ---
            /** @var UploadedFile $portraitFile */
            $portraitFile = $form->get('portrait')->getData();
            if ($portraitFile) {
                $newFilename = uniqid().'.'.$portraitFile->guessExtension();
                try {
                    $portraitFile->move($this->getParameter('portraits_directory'), $newFilename);
                    $personnage->setPortrait($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur sur le portrait.');
                }
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_personnage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('personnage/edit.html.twig', [
            'personnage' => $personnage,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_personnage_delete', methods: ['POST'])]
    public function delete(Request $request, Personnage $personnage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $personnage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($personnage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_personnage_index', [], Response::HTTP_SEE_OTHER);
    }
}
