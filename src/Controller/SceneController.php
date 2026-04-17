<?php

namespace App\Controller;

use App\Entity\ElementScene;
use App\Entity\Scene;
use App\Form\SceneType;
use App\Repository\ElementSceneRepository;
use App\Repository\InstrumentsRepository;
use App\Repository\SceneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;


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
                $musiciens[$nom] = [
                    'nom' => $nom,
                    'instruments' => [],
                ];
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
                if (empty($musicien['instrument_id'])) {
                    continue;
                }

                foreach ($musicien['instrument_id'] as $instrumentId) {
                    $element = new ElementScene();
                    $element->setScene($scene);
                    $element->setNomMusicien($musicien['nom']);
                    $element->setQuantite(1);
                    $element->setPositionX(0);
                    $element->setPositionY(0);

                    $instrument = $instrumentsRepository->find($instrumentId);
                    $element->setInstrument($instrument);
                    $em->persist($element);
                }
            }

            $em->flush();

            return $this->redirectToRoute('app_scene_form_edit', ['id' => $scene->getId()]);
        }

        return $this->render('scene/form.html.twig', [
            'form' => $form,
            'scene' => $scene,
            'musiciens' => $musiciens,
            'instruments' => $instruments,
            'isNew' => $isNew,
        ]);
    }
    #[Route('/scene/{id}/delete', name: 'app_scene_delete', methods: ['POST'])]
    public function delete(Scene $scene,EntityManagerInterface $em,Request $request,CsrfTokenManagerInterface $csrfTokenManager): Response {
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
    public function deleteMusicien(string $nom,int $sceneId,EntityManagerInterface $em,ElementSceneRepository $elementSceneRepository,Request $request,CsrfTokenManagerInterface $csrfTokenManager): Response {
        $token = new CsrfToken('delete_musicien' . $nom, $request->request->get('_token'));

        if (!$csrfTokenManager->isTokenValid($token)) {
            return $this->redirectToRoute('app_scene_index');
        }

        $elements = $elementSceneRepository->findBy([
            'scene' => $sceneId,
            'nom_musicien' => $nom,
            
        ]);
dd($elements);
        foreach ($elements as $element) {
            $em->remove($element);
        }

        $em->flush();

        return $this->redirectToRoute('app_scene_form_edit', ['id' => $sceneId]);
    }
}