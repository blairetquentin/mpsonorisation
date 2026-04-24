<?php
namespace App\Controller;

use App\Form\MusicienType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SceneController extends AbstractController{
    #[Route('/scene', name: 'app_scene')]
    public function index(Request $request) : Response 
    {
        $data =[
            'nom' => '',
            'instruments' => [],
        ];

        $form = $this->createForm(MusicienType::class, $data);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
        }

            return $this->render('scene/index.html.twig', [
                'form' => $form,
            ]);

    }

}

