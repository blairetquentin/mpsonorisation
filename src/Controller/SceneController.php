<?php

namespace App\Controller;

use App\Entity\ElementScene;
use App\Entity\Scene;
use App\Form\SceneType;
use App\Repository\InstrumentsRepository;
use App\Repository\SceneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SceneController extends AbstractController
{
    #[Route('/scene', name: 'app_scene_index')]
    public function index(SceneRepository $sceneRepository): Response
    {
        // On récupère l'utilisateur connecté (null si pas connecté)
        $user = $this->getUser();

        // Si pas connecté, on envoie quand même le template
        // mais avec connecte = false
        if (!$user) {
            return $this->render('scene/index.html.twig', [
                'connecte' => false,
                'scenes' => [],
            ]);
        }

        // Si connecté, on récupère ses plans de scène
        // findBy = requête avec filtre + tri
        $scenes = $sceneRepository->findBy(
            ['user' => $user],             // WHERE user = $user
            ['date_evenement' => 'DESC']   // ORDER BY date_evenement DESC
        );

        return $this->render('scene/index.html.twig', [
            'connecte' => true,
            'scenes' => $scenes,
        ]);
    }
    #[Route('/scene/create', name: 'app_scene_create')]
    public function create(Request $request,EntityManagerInterface $em,InstrumentsRepository $instrumentsRepository): Response {
        // On vérifie que l'utilisateur est connecté
        // Si non, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // On crée un objet Scene vide
        $scene = new Scene();

        // On crée le formulaire lié à cet objet
        $form = $this->createForm(SceneType::class, $scene);

        // On récupère les données envoyées par l'utilisateur (POST)
        $form->handleRequest($request);

        // On récupère tous les instruments pour la section musiciens
        $instruments = $instrumentsRepository->findAll();

        // Si le formulaire est soumis ET valide
        if ($form->isSubmitted() && $form->isValid()) {

            // On lie la scène à l'utilisateur connecté
            $scene->setUser($this->getUser());

            // Le plan commence toujours en brouillon (statut = false)
            $scene->setStatut(false);

            // On sauvegarde la scène en base
            $em->persist($scene);
            $em->flush();

            // On récupère les musiciens envoyés via le formulaire JS
            // $_POST['musiciens'] = tableau de {nom, instrument_id}
            $musiciens = $request->request->all('musiciens');

            foreach ($musiciens as $musicien) {
                // On vérifie qu'au moins un instrument a été sélectionné
                if (empty($musicien['instrument_id'])) {
                    continue;
                }

                // Pour chaque instrument sélectionné on crée un ElementScene
                foreach ($musicien['instrument_id'] as $instrumentId) {
                    $element = new ElementScene();
                    $element->setScene($scene);
                    $element->setNomMusicien($musicien['nom']);
                    $element->setQuantite(1);
                    // Position par défaut, sera modifiée au drag & drop (SF14)
                    $element->setPositionX(0);
                    $element->setPositionY(0);

                    // On récupère l'objet Instruments depuis son id
                    $instrument = $instrumentsRepository->find($instrumentId);
                    $element->setInstrument($instrument);

                    $em->persist($element);
                }
            }
            // On sauvegarde tous les ElementScene en une fois
            $em->flush();

            // On redirige vers la page du plan (SF14)
            return $this->redirectToRoute('app_scene_index');;
        }

        return $this->render('scene/create.html.twig', [
            'form' => $form,
            'instruments' => $instruments,
        ]);
    }
}