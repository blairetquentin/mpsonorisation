<?php

namespace App\Controller;

use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class CatalogueController extends AbstractController
{
    #[Route('/catalogue', name: 'app_catalogue')]
    public function index(MaterielRepository $materielRepo): Response
   {
        $materiels = $materielRepo->findAllWithRelations();
        $catalogue =[];
        foreach ($materiels as $materiel){
            $cat = $materiel->getSousCategorie()->getCategorie()->getLibelle();
            $scat = $materiel->getSousCategorie()->getLibelle();
            $catalogue[$cat][$scat][] = $materiel;
        }
        return $this->render('catalogue/index.html.twig' ,[
            'catalogue' => $catalogue,
        ]);
}
    #[Route('/catalogue/{id}', name: 'app_catalogue_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, MaterielRepository $materielRepo): Response
    {
        $materiel = $materielRepo->find($id);

        if(! $materiel){
            throw $this->createNotFoundException('materiel introuvable');
        }
        return $this->render('catalogue/detail.html.twig' , [
            'materiel' => $materiel,
        ]);
}}