<?php
namespace App\Controller;

use App\Entity\ElementScene;
use App\Entity\Scene;
use App\Form\EvenementsceneType;
use App\Repository\ElementSceneRepository;
use App\Repository\InstrumentsRepository;
use App\Repository\SceneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SceneController extends AbstractController
{
    #[Route('/scene' , name : 'app_scene')]
    public function index(SceneRepository $sceneRepository) : Response
    {
        $scenes = $sceneRepository->findAll();

        return $this->render('scene/index.html.twig', [
            'scenes' => $scenes,
        ]);
    }

    #[Route('/scene/{id?}', name: 'app_scene_form')]
    public function maScene($id = null,Request $request,SceneRepository $sceneRepository,EntityManagerInterface $emi,InstrumentsRepository $instrumentsRepository) : Response {
        if ($id) {
            $scene = $sceneRepository->find($id);
        } else {
            $scene = new Scene();
        }

        $instruments = $instrumentsRepository->findBy(['type' => 'instrument']);
        $equipements = $instrumentsRepository->findBy(['type' => 'equipement']);
        $form = $this->createForm(EvenementsceneType::class, $scene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $scene->setUser($this->getUser());
            $emi->persist($scene);

            $action = $request->request->get('action');

            if ($action === 'ajouter') {
                $instrumentIds = $request->request->all('instruments');
                $nomMusiciens  = $request->request->all('nomMusicien');

                foreach ($instrumentIds as $index => $instrumentId) {
                    $instrument = $instrumentsRepository->find($instrumentId);
                    if (!$instrument) {
                        continue;
                    }
                    $elementscene = new ElementScene();
                    $elementscene->setScene($scene);
                    $elementscene->setInstrument($instrument);
                    $elementscene->setNomMusicien($nomMusiciens[$index] ?? '');
                    $emi->persist($elementscene);
                }
                $emi->flush();
            }

            if ($action === 'equipement') {
                $equipementIds = $request->request->all('equipement');
                foreach ($equipementIds as $elementSceneId => $equipementId) {
                    $elementScene = $emi->getRepository(ElementScene::class)->find($elementSceneId);
                    if ($elementScene && $equipementId) {
                        $equipement = $instrumentsRepository->find($equipementId);
                        $elementScene->setEquipement($equipement);
                        $emi->persist($elementScene);
                    }
                }
                $emi->flush();
            }
        }

        return $this->render('scene/edit.html.twig', [
            'scene'       => $scene,
            'form'        => $form,
            'instruments' => $instruments,
            'equipements' => $equipements,
        ]);
    }
    
        #[Route('scene/element/{id}/delete', name: 'app_scene_delete', methods: ['POST'])]
        public function delete(int $id, ElementSceneRepository $elementSceneRepository, EntityManagerInterface $emi, Request $request) : Response
        {
            $elementscene = $elementSceneRepository->find($id);
            
            if (!$elementscene) {
                return $this->redirectToRoute('app_scene');
            }

            if (!$this->isCsrfTokenValid('delete_element_' . $id, $request->request->get('_token'))) {
                throw $this->createAccessDeniedException('Token CSRF invalide.');
            }

            $sceneId = $elementscene->getScene()->getId();
            $emi->remove($elementscene);
            $emi->flush();

            return $this->redirectToRoute('app_scene_form', ['id' => $sceneId]);
        }
        
    }
