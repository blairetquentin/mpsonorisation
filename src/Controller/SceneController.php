<?php
namespace App\Controller;

use App\Entity\ConfigBatterie;
use App\Entity\ElementScene;
use App\Entity\MaterielSuggere;
use App\Entity\Scene;
use App\Form\EvenementsceneType;
use App\Repository\ConfigBatterieRepository;
use App\Repository\ElementSceneRepository;
use App\Repository\InstrumentsRepository;
use App\Repository\MaterielSuggereRepository;
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

    #[Route('/scene/mascene/{id}', name:'app_scene_mascene')]
    public function mascene(int $id, SceneRepository $sceneRepository,  ConfigBatterieRepository $configBatterieRepository) : Response
    {
        $scene= $sceneRepository->find($id);
        return $this->render('scene/mascene.html.twig', [
            'scene' => $scene,
         ]);
    }

    #[Route('scene/equipement/{id}', name:'app_scene_mesequipements')]
    public function mesequipements(int $id, SceneRepository $sceneRepository,ElementSceneRepository $elementSceneRepository, InstrumentsRepository $instrumentsRepository, Request $request, EntityManagerInterface $emi) : Response
    {
        $scene= $sceneRepository->find($id);
        $instruments = $instrumentsRepository->findBy(['type' => 'instrument']);
        $equipements = $instrumentsRepository->findBy(['type' => 'equipement']);

        if($request->isMethod('POST')){
            $equipementsChoisis = $request->request->all('equipement');
            foreach($equipementsChoisis as $elementSceneId => $equipementId){
                $elementScene = $elementSceneRepository->find($elementSceneId);
                if(!$elementScene) {
                    continue;
                }
                $equipement = $instrumentsRepository->find($equipementId);
                if(!$equipement) {
                    continue;
                }
                $elementScene->setEquipement($equipement);
                $emi->persist($elementScene);
            }
            $emi->flush();
        };
        
        return $this->render('scene/mesequipements.html.twig', [
            'scene' => $scene,
            'instruments' => $instruments,
            'equipements' => $equipements,
        ]);
    }

    #[Route('/scene/cree', name: 'app_scene_form')]
    public function cree(Request $request, EntityManagerInterface $emi, InstrumentsRepository $instrumentsRepository) : Response 
    {
        $scene = new Scene();
        $form = $this->createForm(EvenementsceneType::class, $scene);
        $form->handleRequest($request);
        $instruments = $instrumentsRepository->findBy(['type' => 'instrument']);

        if ($form->isSubmitted() && $form->isValid()) {
            $scene->setUser($this->getUser());
            $emi->persist($scene);

            $instrumentIds = $request->request->all('instruments');
            $nomMusiciens  = $request->request->all('nomMusicien');
            foreach ($instrumentIds as $index =>$instrumentId) {
                $instrument = $instrumentsRepository->find($instrumentId);
                if (!$instrument) {
                        continue;
                    }
                $elementscene = new ElementScene();
                $elementscene->setScene($scene);
                $elementscene->setInstrument($instrument);
                $elementscene->setNomMusicien($nomMusiciens[$index]);
                $emi->persist($elementscene);
            };
            $emi->flush();
    
            return $this->redirectToRoute('app_scene');
        }
    return $this->render('scene/cree.html.twig', [
        'scene' => $scene,
        'form' => $form,
        'instruments' => $instruments,
    ]);
    }
    
    #[Route('scene/modifier/{id}', name: 'app_scene_modif')]
    public function modifier(int $id, Request $request, SceneRepository $sceneRepository, EntityManagerInterface $emi, InstrumentsRepository $instrumentsRepository) : Response
    {
        $scene = $sceneRepository->find($id);
        $form = $this->createForm(EvenementsceneType::class, $scene);
        $form->handleRequest($request);
        $instruments = $instrumentsRepository->findBy(['type' => 'instrument']);

        if ($form->isSubmitted() && $form->isValid()) {
            $scene->setUser($this->getUser());
            $emi->persist($scene);

            $instrumentIds = $request->request->all('instruments');
            $nomMusiciens  = $request->request->all('nomMusicien');
            foreach ($instrumentIds as $index =>$instrumentId) {
                $instrument = $instrumentsRepository->find($instrumentId);
                if (!$instrument) {
                        continue;
                    }
                $elementscene = new ElementScene();
                $elementscene->setScene($scene);
                $elementscene->setInstrument($instrument);
                $elementscene->setNomMusicien($nomMusiciens[$index]);
                $emi->persist($elementscene);
            };
            $emi->flush();
    
            return $this->redirectToRoute('app_scene_modif',['id' => $id]);
        }
    return $this->render('scene/modifier.html.twig', [
        'scene' => $scene,
        'form' => $form,
        'instruments' => $instruments,
    ]);
    }

    #[Route('scene/batterie/{id}', name: 'app_scene_batterie')]
    public function batterie(int $id, ElementSceneRepository $elementSceneRepository, ConfigBatterieRepository $configBatterieRepository, Request $request, EntityManagerInterface $emi) : Response {
        $batterie = $elementSceneRepository->find($id);
        $configbatterie = $configBatterieRepository->findOneBy(['elementScene' => $batterie]);
        if($request->isMethod('POST')){
            if (!$configbatterie) {
                $configbatterie = new ConfigBatterie();
                $configbatterie->setElementScene($batterie);
            };
            $configbatterie->setNbToms($request->request->get('NbToms'));
            $configbatterie->setNbCaisseClaire($request->request->get('NbCaisseClaire'));
            $configbatterie->setNbCharleston($request->request->get('NbCharleston'));
            $configbatterie->setNbCymbales($request->request->get('NbCymbales'));
            $configbatterie->setNbGrosseCaisse($request->request->get('NbGrosseCaisse'));
            $emi->persist($configbatterie);
            $emi->flush();
            }
        
            return $this->render('scene/mabatterie.html.twig',[
            'batterie'=>$batterie,
            'configbatterie'=>$configbatterie,
            'scene' => $batterie->getScene(),
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

        return $this->redirectToRoute('app_scene_modif', ['id' => $sceneId]);
    }

    #[Route('scene/materielsuggere/{id}', name:'app_scene_materielsuggere')]
    public function materielconseille(int $id, InstrumentsRepository $instrumentsRepository, ConfigBatterieRepository $configBatterieRepository, SceneRepository $sceneRepository, Request $request, ElementSceneRepository $elementSceneRepository, MaterielSuggereRepository $materielSuggereRepository, EntityManagerInterface $emi) : Response
    {
        $scene = $sceneRepository->find($id);
        $equipementsBatterie = $instrumentsRepository->findBy(['type' => 'equipement_batterie']);

        if($request->isMethod('POST')) {
            $materielsChoisis = $request->request->all('equipement');
            $batterieChoisis = $request->request->all('batterie');

            foreach($materielsChoisis as $elementSceneId => $materielSuggereId) {
                $elementScene = $elementSceneRepository->find($elementSceneId);
                if (!$elementScene) {
                    continue;
                }
                $materielSuggere = $materielSuggereRepository->find($materielSuggereId);
                $elementScene->setMaterielSuggere($materielSuggere);
                $emi->persist($elementScene);
            }

            $configBatterie = null;
            foreach($scene->getElementScenes() as $element) {
                if($element->getConfigBatterie()) {
                    $configBatterie = $element->getConfigBatterie();
                    break;
                }
            }

            if($configBatterie && $batterieChoisis) {
                $configBatterie->setMicroTom($materielSuggereRepository->find($batterieChoisis['tom'][1] ?? null));
                $configBatterie->setMicroCymbale($materielSuggereRepository->find($batterieChoisis['cymbale'][1] ?? null));
                $configBatterie->setMicroGrosseCaisse($materielSuggereRepository->find($batterieChoisis['grosseCaisse'][1] ?? null));
                $configBatterie->setMicroCaisseClaire($materielSuggereRepository->find($batterieChoisis['caisseClaire'][1] ?? null));
                $configBatterie->setMicroCharleston($materielSuggereRepository->find($batterieChoisis['charleston'][1] ?? null));
                $emi->persist($configBatterie);
            }

            $emi->flush();
        }

        return $this->render('scene/materielsuggere.html.twig', [
            'scene' => $scene,
            'equipementsBatterie' => $equipementsBatterie,
        ]);
    }
    }
