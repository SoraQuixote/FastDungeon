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
        if ($this->isGranted('ROLE_ADMIN')) {
            $personnages = $personnageRepository->findAll();
        } else {
            $personnages = $personnageRepository->findBy(['user' => $this->getUser()]);
        }

        return $this->render('personnage/index.html.twig', [
            'personnages' => $personnages,
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
            $dataPersonnage = $request->request->all('personnage');

            // --- 1. SAUVEGARDE DES ATTAQUES ---
            $attaquesData = $dataPersonnage['attaques'] ?? [];
            foreach ($personnage->getAttaques() as $oldAtk) {
                $entityManager->remove($oldAtk);
            }
            foreach ($attaquesData as $data) {
                if (empty($data['nom'])) continue;
                $type = strtolower(trim($data['type'] ?? ''));
                if ($type === 'magique') {
                    $atk = new \App\Entity\AttaqueMagique();
                    $atk->setPtsDeVie((int)($data['ptsDeVie'] ?? 0));
                    $atk->setType($data['magieType'] ?? 'Magie Noire');
                } else {
                    $atk = new \App\Entity\AttaquePhysique();
                    $atk->setDegatDeContre((int)($data['contre'] ?? 0));
                }
                $atk->setNom($data['nom'])->setPortee($data['portee'] ?? 'Contact')->setEffet($data['effet'] ?? '')
                    ->setDescription($data['desc'] ?? '')->setPtsDegat((int)($data['degat'] ?? 0))->setCout((int)($data['cout'] ?? 0));
                $personnage->addAttaque($atk);
                $entityManager->persist($atk);
            }

            // --- 2. SAUVEGARDE DES OBJETS (MANQUANT DANS TON CODE) ---
            $objetsData = $dataPersonnage['objets'] ?? [];
            foreach ($personnage->getObjets() as $oldObj) {
                $entityManager->remove($oldObj);
            }
            foreach ($objetsData as $oData) {
                if (empty($oData['nom'])) continue;
                $obj = new \App\Entity\Objet();
                $obj->setNom($oData['nom'])
                    ->setPtsDegat((int)($oData['ptsDegat'] ?? 0))
                    ->setPtsDeVie((int)($oData['ptsDeVie'] ?? 0))
                    ->setEffet($oData['effet'] ?? '')
                    ->setDescription($oData['description'] ?? '');
                $personnage->addObjet($obj);
                $entityManager->persist($obj);
            }

            // --- 3. ARME ET ARMURE ---
            if (isset($dataPersonnage['arme'])) {
                $arme = $personnage->getArme() ?? new \App\Entity\Arme();
                $arme->setNom($dataPersonnage['arme']['nom'] ?? '')
                    // Utilisation de setBonus car c'est le nom dans ton MCD pour l'Arme
                    ->setBonus((int)($dataPersonnage['arme']['bonus'] ?? 0)) 
                    ->setDescription($dataPersonnage['arme']['description'] ?? '');
                $personnage->setArme($arme);
                $entityManager->persist($arme);
            }

            if (isset($dataPersonnage['armure'])) {
                $armure = $personnage->getArmure() ?? new \App\Entity\Armure();
                $armure->setNom($dataPersonnage['armure']['nom'] ?? '')
                    ->setBonus((int)($dataPersonnage['armure']['bonus'] ?? 0))
                    ->setDescription($dataPersonnage['armure']['description'] ?? '');
                $personnage->setArmure($armure);
                $entityManager->persist($armure);
            }

            // --- 4. PORTRAIT ---
            /** @var UploadedFile $portraitFile */
            $portraitFile = $form->get('portrait')->getData();
            if ($portraitFile) {
                $newFilename = uniqid().'.'.$portraitFile->guessExtension();
                $portraitFile->move($this->getParameter('portraits_directory'), $newFilename);
                $personnage->setPortrait($newFilename);
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_personnage_index');
        }

        return $this->render('personnage/edit.html.twig', [
            'personnage' => $personnage,
            'form' => $form,
        ]);
    }
}
