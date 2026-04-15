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

    #[Route('/scene/create', name: 'app_scene_create')]
    public function create(Request $request, EntityManagerInterface $em, InstrumentsRepository $instrumentsRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $scene = new Scene();
        $form = $this->createForm(SceneType::class, $scene);
        $form->handleRequest($request);
        $instruments = $instrumentsRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $scene->setUser($this->getUser());
            $scene->setStatut(false);
            $em->persist($scene);
            $em->flush();

            $musiciens = $request->request->all('musiciens');

            foreach ($musiciens as $musicien) {
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

            return $this->redirectToRoute('app_scene_index');
        }

        return $this->render('scene/create.html.twig', [
            'form' => $form,
            'instruments' => $instruments,
        ]);
    }
}