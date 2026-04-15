<?php

namespace App\Controller\Admin;

use App\Repository\CategorieRepository;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DevisDetailController extends AbstractController
{
    #[Route('/admin/devis/{id}/detail', name: 'admin_devis_detail_custom')]
    public function detail(int $id, DevisRepository $devisRepo, CategorieRepository $categorieRepo): Response
    {
        $devis = $devisRepo->find($id);

        if (!$devis) {
            throw $this->createNotFoundException('Devis introuvable');
        }

        $panier = $devis->getPanier();
        $user = $panier->getUser();

        return $this->render('admin/devis/detail.html.twig', [
            'devis' => $devis,
            'panier' => $panier,
            'user' => $user,
            'categories' => $categorieRepo->findAll(),
        ]);
    }
    #[Route('/admin/devis/{devisId}/materiel/{panierId}/supprimer', name: 'admin_devis_supprimer_materiel', methods: ['POST'])]
    public function supprimerMateriel(int $devisId, int $panierId, \App\Repository\PanierMaterielRepository $panierMaterielRepo, \Doctrine\ORM\EntityManagerInterface $em, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        if (!$this->isCsrfTokenValid('supprimer_materiel_' . $panierId, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token invalide.');
            return $this->redirectToRoute('admin_devis_detail_custom', ['id' => $devisId]);
        }

        $panierMateriel = $panierMaterielRepo->find($panierId);

        if ($panierMateriel) {
            $em->remove($panierMateriel);
            $em->flush();
            $this->addFlash('success', 'Matériel supprimé.');
        }

        return $this->redirectToRoute('admin_devis_detail_custom', ['id' => $devisId]);
    }
    #[Route('/admin/devis/{devisId}/materiel/{panierId}/quantite', name: 'admin_devis_modifier_quantite', methods: ['POST'])]
    public function modifierQuantite(int $devisId, int $panierId, \App\Repository\PanierMaterielRepository $panierMaterielRepo, \Doctrine\ORM\EntityManagerInterface $em, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        if (!$this->isCsrfTokenValid('modifier_quantite_' . $panierId, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token invalide.');
            return $this->redirectToRoute('admin_devis_detail_custom', ['id' => $devisId]);
        }

        $panierMateriel = $panierMaterielRepo->find($panierId);

        if ($panierMateriel) {
            $quantite = (int) $request->request->get('quantite');
            if ($quantite > 0) {
                $panierMateriel->setQuantite($quantite);
                $em->flush();
                $this->addFlash('success', 'Quantité modifiée.');
            }
        }

        return $this->redirectToRoute('admin_devis_detail_custom', ['id' => $devisId]);
    }

    #[Route('/admin/devis/{devisId}/materiel/{panierId}/remplacer', name: 'admin_devis_remplacer_materiel', methods: ['POST'])]
    public function remplacerMateriel(int $devisId, int $panierId, \App\Repository\PanierMaterielRepository $panierMaterielRepo, \App\Repository\MaterielRepository $materielRepo, \Doctrine\ORM\EntityManagerInterface $em, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        if (!$this->isCsrfTokenValid('remplacer_materiel_' . $panierId, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token invalide.');
            return $this->redirectToRoute('admin_devis_detail_custom', ['id' => $devisId]);
        }

        $panierMateriel = $panierMaterielRepo->find($panierId);
        $nouveauMateriel = $materielRepo->find($request->request->get('materiel_id'));

        if ($panierMateriel && $nouveauMateriel) {
            $panierMateriel->setMateriel($nouveauMateriel);
            $em->flush();
            $this->addFlash('success', 'Matériel remplacé.');
        }

        return $this->redirectToRoute('admin_devis_detail_custom', ['id' => $devisId]);
    }

    #[Route('/admin/devis/{id}/traiter', name: 'admin_devis_traiter', methods: ['POST'])]
    public function traiter(int $id, DevisRepository $devisRepo, EntityManagerInterface $em, Request $request, MailerInterface $mailer): Response
    {
        $devis = $devisRepo->find($id);

        if (!$devis) {
            throw $this->createNotFoundException('Devis introuvable');
        }

        if (!$this->isCsrfTokenValid('traiter_devis_' . $id, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token invalide.');
            return $this->redirectToRoute('admin_devis_detail_custom', ['id' => $id]);
        }

        $statut = $request->request->get('statut');
        $commentaire = $request->request->get('commentaire');

        $devis->setStatut($statut);
        $devis->setCommentaireAdmin($commentaire);
        $em->flush();
        if ($statut === 'refuse') {
            $em->remove($devis);
            $em->flush();
            }


        $user = $devis->getPanier()->getUser();
        $email = (new \Symfony\Component\Mime\Email())
            ->from('blairet.quentin@gmail.com')
            ->to($user->getEmail())
            ->subject('Réponse à votre demande de devis — MpSonorisation')
            ->html($this->renderView('devis/reponse.html.twig', [
                'devis' => $devis,
                'user' => $user,
            ]));

        $mailer->send($email);

        $this->addFlash('success', 'Devis ' . ($statut === 'valide' ? 'validé' : 'refusé') . ' et email envoyé.');
        return $this->redirectToRoute('admin_devis_detail_custom', ['id' => $id]);
    }

    #[Route('/admin/devis/{devisId}/materiel/{materielId}/prix', name: 'admin_devis_modifier_prix', methods: ['POST'])]
    public function modifierPrix(int $devisId, int $materielId, \App\Repository\MaterielRepository $materielRepo, \Doctrine\ORM\EntityManagerInterface $em, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        if (!$this->isCsrfTokenValid('modifier_prix_' . $materielId, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token invalide.');
            return $this->redirectToRoute('admin_devis_detail_custom', ['id' => $devisId]);
        }

        $materiel = $materielRepo->find($materielId);

        if ($materiel) {
            $materiel->setPrix((float) $request->request->get('prix'));
            $em->flush();
            $this->addFlash('success', 'Prix modifié.');
        }

        return $this->redirectToRoute('admin_devis_detail_custom', ['id' => $devisId]);
    }
}