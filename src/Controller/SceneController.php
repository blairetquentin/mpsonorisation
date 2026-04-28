<?php
namespace App\Controller;

use App\Entity\ElementScene;
use App\Entity\Scene;
use App\Form\EvenementsceneType;
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

    #[Route('/scene/{id?}' , name: 'app_scene_form')]
    public function maScene($id=null, Request $request, SceneRepository $sceneRepository, EntityManagerInterface $emi, InstrumentsRepository $instrumentsRepository) : Response
    {
       if($id){
        $scene = $sceneRepository->find($id);
       }else{
        $scene = new Scene();
       }
        $instruments = $instrumentsRepository->findAll();
        $form = $this->createForm(EvenementsceneType::class, $scene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
                $scene->setUser($this->getUser());
                $emi->persist($scene);
                $emi->flush();
                
                $elementscene = new ElementScene();
                $instrumentId = $request->request->get('instruments-select');
                $instrument = $instrumentsRepository->find($instrumentId);
                $elementscene->setScene($scene);
                $elementscene->setInstrument($instrument);
                $elementscene->setNomMusicien('test');
                $elementscene->setQuantite(1);
                $emi->persist($elementscene);
                $emi->flush();
            }

            return $this->render('scene/edit.html.twig', [
                'scene' => $scene,
                'form' => $form,
                'instruments'=> $instruments,
            ]);
        }
    }
