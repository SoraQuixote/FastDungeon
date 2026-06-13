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

// Toutes les routes commencent par /personnage
#[Route('/personnage')]
final class PersonnageController extends AbstractController
{
    // Affiche la liste des personnages
    // Les admins voient tous les personnages, les autres voient seulement les leurs
    #[Route(name: 'app_personnage_index', methods: ['GET'])]
    public function index(PersonnageRepository $personnageRepository): Response
    {   
        if ($this->isGranted('ROLE_ADMIN')) {
            $personnages = $personnageRepository->findAll();
        } else {
            // Filtre par utilisateur connecté
            $personnages = $personnageRepository->findBy(['user' => $this->getUser()]);
        }

        return $this->render('personnage/index.html.twig', [
            'personnages' => $personnages,
        ]);
    }

    // Affiche et traite le formulaire de création d'un personnage
    #[Route('/new', name: 'app_personnage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $personnage = new Personnage();
        $personnage->setUser($this->getUser());
        $form = $this->createForm(PersonnageType::class, $personnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataPersonnage = $request->request->all('personnage');

            // --- PORTRAIT ---
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

            // --- STATS ---
            $statsData = $dataPersonnage['stats'] ?? [];
            foreach ($statsData as $sData) {
                if (empty($sData['nom'])) continue;
                $stat = new \App\Entity\Stat();
                $stat->setNom($sData['nom'])
                    ->setValeur((int)($sData['valeur'] ?? 0));
                $personnage->addStat($stat);
                $entityManager->persist($stat);
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

    // Traite la modification complète d'un personnage (stats, attaques, objets, équipement, portrait)
    #[Route('/{id}/edit', name: 'app_personnage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Personnage $personnage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonnageType::class, $personnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les données brutes du formulaire pour les sous-entités (attaques, objets, etc.)
            $dataPersonnage = $request->request->all('personnage');

            // --- 1. SAUVEGARDE DES ATTAQUES ---
            $attaquesData = $dataPersonnage['attaques'] ?? [];
            // Supprime toutes les anciennes attaques avant de les recréer
            foreach ($personnage->getAttaques() as $oldAtk) {
                $entityManager->remove($oldAtk);
            }
           foreach ($attaquesData as $data) {
                if (empty($data['nom'])) continue;
                $type = strtolower(trim($data['type'] ?? ''));
                // Crée une AttaqueMagique ou AttaquePhysique selon le type
                if ($type === 'magique') {
                    $atk = new \App\Entity\AttaqueMagique();
                } else {
                    $atk = new \App\Entity\AttaquePhysique();
                    $atk->setDegatDeContre((int)($data['contre'] ?? 0));
                }
                // Remplit les champs communs à toutes les attaques
                $atk->setNom($data['nom'])->setPortee($data['portee'] ?? 'Contact')->setEffet($data['effet'] ?? '')
                    ->setDescription($data['desc'] ?? '')->setPtsDegat((int)($data['degat'] ?? 0))->setCout((int)($data['cout'] ?? 0));
                $personnage->addAttaque($atk);
                $entityManager->persist($atk);
            }

            // --- 2. SAUVEGARDE DES OBJETS ---
            $objetsData = $dataPersonnage['objets'] ?? [];
            // Supprime tous les anciens objets avant de les recréer
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
            // --- 3. SAUVEGARDE DES STATS ---
            $statsData = $dataPersonnage['stats'] ?? [];
            foreach ($personnage->getStats() as $oldStat) {
                $entityManager->remove($oldStat);
            }
            foreach ($statsData as $sData) {
                if (empty($sData['nom'])) continue;
                $stat = new \App\Entity\Stat();
                $stat->setNom($sData['nom'])
                    ->setValeur((int)($sData['valeur'] ?? 0));
                $personnage->addStat($stat);
                $entityManager->persist($stat);
            }

            // --- 4. ARME ET ARMURE ---
            // Met à jour l'arme existante ou en crée une nouvelle si absente
            if (isset($dataPersonnage['arme'])) {
                $arme = $personnage->getArme() ?? new \App\Entity\Arme();
                $arme->setNom($dataPersonnage['arme']['nom'] ?? '')
                    ->setBonus((int)($dataPersonnage['arme']['bonus'] ?? 0)) 
                    ->setDescription($dataPersonnage['arme']['description'] ?? '');
                $personnage->setArme($arme);
                $entityManager->persist($arme);
            }

            // Met à jour l'armure existante ou en crée une nouvelle si absente
            if (isset($dataPersonnage['armure'])) {
                $armure = $personnage->getArmure() ?? new \App\Entity\Armure();
                $armure->setNom($dataPersonnage['armure']['nom'] ?? '')
                    ->setBonus((int)($dataPersonnage['armure']['bonus'] ?? 0))
                    ->setDescription($dataPersonnage['armure']['description'] ?? '');
                $personnage->setArmure($armure);
                $entityManager->persist($armure);
            }

            // --- 5. PORTRAIT ---
            // Remplace le portrait uniquement si un nouveau fichier est envoyé
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

    // Supprime un personnage après vérification du token CSRF
    #[Route('/{id}/delete', name: 'app_personnage_delete', methods: ['POST'])]
    public function delete(Request $request, Personnage $personnage, EntityManagerInterface $entityManager): Response
    {
        // Vérifie que la requête vient bien du formulaire de suppression (sécurité anti-CSRF)
        if ($this->isCsrfTokenValid('delete'.$personnage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($personnage);
            $entityManager->flush();
            
            $this->addFlash('success', 'Le personnage a disparu dans les abysses.');
        }

        return $this->redirectToRoute('app_personnage_index');
    }
}