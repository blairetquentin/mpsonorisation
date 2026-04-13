<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Panier;
use App\Entity\PanierMateriel;
use App\Repository\MaterielRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mime\Email;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(PanierRepository $panierRepo): Response
    {
        $panier = $panierRepo->findOneBy(['user' => $this->getUser()]);

        return $this->render('panier/index.html.twig',[
            'panier' => $panier,
        ]);
    }

    #[Route('/panier/add/{id}', name: 'app_panier_add', requirements: ['id'=>'\d+'])]
    public function add(int $id, MaterielRepository $materielRepo, PanierRepository $panierRepo, EntityManagerInterface $em): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $materiel = $materielRepo->find($id);

        if (!$materiel){
            throw $this->createNotFoundException('Materiel non trouvé');
        }
        
        $panier = $panierRepo->findOneBy(['user' => $this->getUser()]);
        if (!$panier) {
            $panier = new Panier();
            $panier->setUser($this->getUser());
            $em->persist($panier);
        }

        $panierMateriel = null;
        foreach($panier->getPanierMateriel() as $pm) {
            if ($pm->getMateriel() === $materiel) {
                $panierMateriel = $pm;
                break;
            }
        }
        if ($panierMateriel) {
            $panierMateriel->setQuantite($panierMateriel->getQuantite() + 1);
        }else{
            $panierMateriel = new PanierMateriel();
            $panierMateriel->setPanier($panier);
            $panierMateriel->setMateriel($materiel);
            $panierMateriel->setQuantite(1);
            $em->persist($panierMateriel);
        }

        $em->flush();

        return $this->redirectToRoute('app_catalogue_detail', ['id' => $materiel->getId()]);
    }

    #[Route('/panier/increase/{id}', name: 'app_panier_increase', requirements: ['id'=>'\d+'])]
    public function increase(int $id, EntityManagerInterface $em): Response
    {
        
        $panierMateriel = $em->getRepository(PanierMateriel::class)->find($id);

        if ($panierMateriel) {
            $panierMateriel->setQuantite($panierMateriel->getQuantite() + 1);
            $em->flush();
        }

        return $this->redirectToRoute('app_panier');
    }
    #[Route('/panier/decrease/{id}', name: 'app_panier_decrease', requirements: ['id'=>'\d+'])]
    public function decrease(int $id, EntityManagerInterface $em): Response
    {
        
        $panierMateriel = $em->getRepository(PanierMateriel::class)->find($id);

        if ($panierMateriel) {
            if ($panierMateriel->getQuantite() > 1){
            $panierMateriel->setQuantite($panierMateriel->getQuantite() - 1);
            }else{
                $em->remove($panierMateriel);
            }
            $em->flush();
        }
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/remove/{id}', name: 'app_panier_remove', requirements: ['id'=>'\d+'])]
    public function remove(int $id, EntityManagerInterface $em): Response
    {
        $panierMateriel = $em->getRepository(PanierMateriel::class)->find($id);

        if ($panierMateriel){
            $em->remove($panierMateriel);
            $em->flush();
        }
        return $this->redirectToRoute('app_panier');
    }   

   #[Route('/panier/envoyer-devis', name:'app_envoyer_devis')]
public function envoyerDevis(PanierRepository $panierRepo, MailerInterface $mailer): Response
{
    /** @var User $user */
    $user = $this->getUser();
    $panier = $panierRepo->findOneBy(['user' => $user]);

    if (!$panier || $panier->getPanierMateriel()->isEmpty()) {
        $this->addFlash('warning', 'Votre panier est vide.');
        return $this->redirectToRoute('app_panier');
    }

    $emailMessage = (new Email())
    ->from($user->getEmail())
    ->to('blairet.quentin@gmail.com')
    ->cc($user->getEmail())
    ->subject('Nouvelle demande de devis — ' . $user->getPrenom() . ' ' . $user->getNom())
    ->html($this->renderView('devis/index.html.twig', [
        'user' => $user,
        'panier' => $panier,
    ]));
    $mailer->send($emailMessage);

    $this->addFlash('success', 'Votre demande de devis a bien été envoyée !');

    return $this->redirectToRoute('app_panier');
}
}
