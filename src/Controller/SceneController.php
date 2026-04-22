<?php

namespace App\Controller;

use App\Entity\ElementScene;
use App\Entity\Scene;
use App\Entity\ConfigBatterie;
use App\Entity\MaterielSuggere;
use App\Form\SceneType;
use App\Repository\ElementSceneRepository;
use App\Repository\InstrumentsRepository;
use App\Repository\SceneRepository;
use App\Repository\ConfigBatterieRepository;
use App\Repository\MaterielRepository;
use App\Repository\MaterielSuggereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SceneController extends AbstractController
{
    #[Route('/scene', name: 'app_scene_index')]
    public function index(SceneRepository $sceneRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->render('scene/index.html.twig', [
                'connecte' => false,
                'scenes' => [],
            ]);
        }

        $scenes = $sceneRepository->findBy(
            ['user' => $user],
            ['date_evenement' => 'DESC']
        );

        return $this->render('scene/index.html.twig', [
            'connecte' => true,
            'scenes' => $scenes,
        ]);
    }

    #[Route('/scene/form', name: 'app_scene_form')]
    #[Route('/scene/{id}/form', name: 'app_scene_form_edit')]
    public function form(
        Request $request,
        EntityManagerInterface $em,
        InstrumentsRepository $instrumentsRepository,
        ElementSceneRepository $elementSceneRepository,
        ?Scene $scene = null
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $isNew = $scene === null;

        if ($isNew) {
            $scene = new Scene();
        } else {
            if ($scene->getUser() !== $this->getUser()) {
                return $this->redirectToRoute('app_scene_index');
            }
        }

        $form = $this->createForm(SceneType::class, $scene);
        $form->handleRequest($request);
        $instruments = $instrumentsRepository->findAll();
        $elements = $isNew ? [] : $elementSceneRepository->findBy(['scene' => $scene]);

        $musiciens = [];
        foreach ($elements as $element) {
            $nom = $element->getNomMusicien();
            if (!isset($musiciens[$nom])) {
                $musiciens[$nom] = ['nom' => $nom, 'instruments' => []];
            }
            $musiciens[$nom]['instruments'][] = $element->getInstrument()->getLibelle();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if ($isNew) {
                $scene->setUser($this->getUser());
                $scene->setStatut(false);
                $em->persist($scene);
            }

            $em->flush();

            $nouveauxMusiciens = $request->request->all('musiciens');

            foreach ($nouveauxMusiciens as $musicien) {
                if (empty($musicien['instrument_id'])) continue;

                foreach ($musicien['instrument_id'] as $instrumentId) {
                    $element = new ElementScene();
                    $element->setScene($scene);
                    $element->setNomMusicien($musicien['nom']);
                    $element->setQuantite(1);
                    $element->setPositionX(0);
                    $element->setPositionY(0);
                    $element->setInstrument($instrumentsRepository->find($instrumentId));
                    $em->persist($element);
                }
            }

            $em->flush();

            return $this->redirectToRoute('app_scene_form_edit', ['id' => $scene->getId()]);
        }

        return $this->render('scene/form.html.twig', [
            'form'       => $form,
            'scene'      => $scene,
            'musiciens'  => $musiciens,
            'instruments' => $instruments,
            'isNew'      => $isNew,
        ]);
    }

    #[Route('/scene/element/{id}/position', name: 'app_scene_element_position', methods: ['POST'])]
    public function updatePosition(ElementScene $element, EntityManagerInterface $em, Request $request): JsonResponse
    {
        if (!$this->getUser()) {
            return new JsonResponse(['success' => false], 403);
        }

        $data = json_decode($request->getContent(), true);
        $element->setPositionX($data['positionX']);
        $element->setPositionY($data['positionY']);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/scene/{id}/plan', name: 'app_scene_plan')]
    public function plan(
        Scene $scene,
        ElementSceneRepository $elementSceneRepository,
        InstrumentsRepository $instrumentsRepository,
        MaterielSuggereRepository $materielSuggereRepository
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($scene->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_scene_index');
        }

        $elements    = $elementSceneRepository->findBy(['scene' => $scene]);
        $equipements = $instrumentsRepository->findBy(['type' => 'equipement']);
        $suggestions = $materielSuggereRepository->findBy(['scene' => $scene]);

        $recapEquipements = [];
        foreach ($elements as $element) {
            if (
                $element->getInstrument()->getType() === 'equipement'
                && ($element->getPositionX() != 0 || $element->getPositionY() != 0)
            ) {
                $libelle = $element->getInstrument()->getLibelle();
                if (!isset($recapEquipements[$libelle])) $recapEquipements[$libelle] = 0;
                $recapEquipements[$libelle]++;
            }
        }

        return $this->render('scene/plan.html.twig', [
            'scene'            => $scene,
            'elements'         => $elements,
            'equipements'      => $equipements,
            'recapEquipements' => $recapEquipements,
            'suggestions'      => $suggestions,
        ]);
    }

    #[Route('/scene/{id}/delete', name: 'app_scene_delete', methods: ['POST'])]
    public function delete(
        Scene $scene,
        EntityManagerInterface $em,
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager
    ): Response {
        if ($scene->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_scene_index');
        }

        $token = new CsrfToken('delete' . $scene->getId(), $request->request->get('_token'));
        if (!$csrfTokenManager->isTokenValid($token)) {
            return $this->redirectToRoute('app_scene_index');
        }

        $em->remove($scene);
        $em->flush();

        return $this->redirectToRoute('app_scene_index');
    }

    #[Route('/musicien/{nom}/delete/{sceneId}', name: 'app_musicien_delete', methods: ['POST'])]
    public function deleteMusicien(
        string $nom,
        int $sceneId,
        EntityManagerInterface $em,
        ElementSceneRepository $elementSceneRepository,
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager
    ): Response {
        $token = new CsrfToken('delete_musicien' . $nom, $request->request->get('_token'));
        if (!$csrfTokenManager->isTokenValid($token)) {
            return $this->redirectToRoute('app_scene_index');
        }

        $elements = $elementSceneRepository->findBy([
            'scene'        => $sceneId,
            'nom_musicien' => $nom,
        ]);

        foreach ($elements as $element) {
            $em->remove($element);
        }

        $em->flush();

        return $this->redirectToRoute('app_scene_form_edit', ['id' => $sceneId]);
    }

    #[Route('/scene/{id}/equipement/{instrumentId}', name: 'app_scene_add_equipement', methods: ['POST'])]
    public function addEquipement(
        Scene $scene,
        int $instrumentId,
        Request $request,
        EntityManagerInterface $em,
        InstrumentsRepository $instrumentsRepository
    ): JsonResponse {
        if (!$this->getUser()) {
            return new JsonResponse(['success' => false], 403);
        }

        $data       = json_decode($request->getContent(), true);
        $instrument = $instrumentsRepository->find($instrumentId);

        if (!$instrument) {
            return new JsonResponse(['success' => false], 404);
        }

        $element = new ElementScene();
        $element->setScene($scene);
        $element->setNomMusicien($instrument->getLibelle());
        $element->setInstrument($instrument);
        $element->setQuantite(1);
        $element->setPositionX($data['positionX']);
        $element->setPositionY($data['positionY']);
        $em->persist($element);
        $em->flush();

        return new JsonResponse(['success' => true, 'id' => $element->getId()]);
    }

    #[Route('/scene/element/{id}/batterie', name: 'app_scene_config_batterie', methods: ['POST'])]
    public function configBatterie(
        ElementScene $element,
        Request $request,
        EntityManagerInterface $em,
        ConfigBatterieRepository $configBatterieRepository
    ): JsonResponse {
        if (!$this->getUser()) {
            return new JsonResponse(['success' => false], 403);
        }

        $data   = json_decode($request->getContent(), true);
        $config = $configBatterieRepository->findOneBy(['elementScene' => $element]);

        if (!$config) {
            $config = new ConfigBatterie();
            $config->setElementScene($element);
            $em->persist($config);
        }

        $config->setNbToms($data['nbToms'] ?? null);
        $config->setNbCymbales($data['nbCymbales'] ?? null);
        $config->setNbCaisseClaire($data['nbCaisseClaire'] ?? null);
        $config->setNbGrosseCaisse($data['nbGrosseCaisse'] ?? null);
        $config->setNbCharleston($data['nbCharleston'] ?? null);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/scene/element/{id}/batterie/delete', name: 'app_scene_delete_batterie', methods: ['POST'])]
    public function deleteBatterie(
        ElementScene $element,
        EntityManagerInterface $em,
        ConfigBatterieRepository $configBatterieRepository
    ): JsonResponse {
        if (!$this->getUser()) {
            return new JsonResponse(['success' => false], 403);
        }

        $config = $configBatterieRepository->findOneBy(['elementScene' => $element]);
        if ($config) {
            $em->remove($config);
            $em->flush();
        }

        return new JsonResponse(['success' => true]);
    }

    #[Route('/scene/{id}/suggestions', name: 'app_scene_suggestions', methods: ['POST'])]
    public function genererSuggestions(
        Scene $scene,
        EntityManagerInterface $em,
        ElementSceneRepository $elementSceneRepository,
        MaterielRepository $materielRepository,
        MaterielSuggereRepository $materielSuggereRepository,
        ConfigBatterieRepository $configBatterieRepository
    ): JsonResponse {
        if (!$this->getUser()) {
            return new JsonResponse(['success' => false], 403);
        }

        foreach ($materielSuggereRepository->findBy(['scene' => $scene]) as $ancienne) {
            $em->remove($ancienne);
        }
        $em->flush();

        $elements = $elementSceneRepository->findBy(['scene' => $scene]);

        $correspondance = [
            'Micro chant'   => 'SON-MIC-001',
            'Ampli guitare' => 'SON-MIC-004',
            'Ampli basse'   => 'SON-MIC-005',
        ];

        $suggestions = [];

        foreach ($elements as $element) {
            if ($element->getPositionX() == 0 && $element->getPositionY() == 0) continue;

            $libelle = $element->getInstrument()->getLibelle();

            if (isset($correspondance[$libelle])) {
                $ref = $correspondance[$libelle];
                if (!isset($suggestions[$ref])) $suggestions[$ref] = 0;
                $suggestions[$ref]++;
            }

            if ($libelle === 'Batterie') {
                $config = $configBatterieRepository->findOneBy(['elementScene' => $element]);
                if ($config) {
                    $nbSM57 = ($config->getNbToms() ?? 0) + ($config->getNbCaisseClaire() ?? 0);
                    if ($nbSM57 > 0) {
                        if (!isset($suggestions['SON-MIC-006'])) $suggestions['SON-MIC-006'] = 0;
                        $suggestions['SON-MIC-006'] += $nbSM57;
                    }
                    $nbBeta52 = $config->getNbGrosseCaisse() ?? 0;
                    if ($nbBeta52 > 0) {
                        if (!isset($suggestions['SON-MIC-005'])) $suggestions['SON-MIC-005'] = 0;
                        $suggestions['SON-MIC-005'] += $nbBeta52;
                    }
                    $nbSM81 = $config->getNbCymbales() ?? 0;
                    if ($nbSM81 > 0) {
                        if (!isset($suggestions['SON-MIC-007'])) $suggestions['SON-MIC-007'] = 0;
                        $suggestions['SON-MIC-007'] += $nbSM81;
                    }
                }
            }
        }

        $resultat = [];
        foreach ($suggestions as $reference => $quantite) {
            $materiel = $materielRepository->findOneBy(['reference' => $reference]);
            if (!$materiel) continue;

            $suggestion = new MaterielSuggere();
            $suggestion->setScene($scene);
            $suggestion->setMateriel($materiel);
            $suggestion->setQuantite($quantite);
            $em->persist($suggestion);

            $resultat[] = ['libelle' => $materiel->getLibelle(), 'quantite' => $quantite];
        }

        $em->flush();

        return new JsonResponse(['success' => true, 'suggestions' => $resultat]);
    }

    #[Route('/scene/{id}/submit', name: 'app_scene_submit', methods: ['POST'])]
    public function submit(
        Scene $scene,
        EntityManagerInterface $em,
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($scene->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_scene_index');
        }

        $token = new CsrfToken('submit' . $scene->getId(), $request->request->get('_token'));
        if (!$csrfTokenManager->isTokenValid($token)) {
            return $this->redirectToRoute('app_scene_index');
        }

        if ($scene->getStatut()) {
            $this->addFlash('warning', 'Ce plan a déjà été soumis.');
            return $this->redirectToRoute('app_scene_index');
        }

        $scene->setStatut(true);
        $em->flush();

        $this->addFlash('success', 'Plan soumis avec succès !');

        return $this->redirectToRoute('app_scene_plan', ['id' => $scene->getId()]);
    }
}