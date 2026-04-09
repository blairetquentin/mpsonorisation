<?php

namespace App\Controller;

use App\Form\ProfilFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(HttpFoundationRequest $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfilFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash("succés","Profil mis a jour !!");
            return $this->redirectToRoute('app_profil');
        }
        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'form'=> $form,
        ]);
    }
}
