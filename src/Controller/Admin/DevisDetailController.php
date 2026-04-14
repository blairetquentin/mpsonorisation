<?php

namespace App\Controller\Admin;

use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DevisDetailController extends AbstractController
{
    #[Route('/admin/devis/{id}/detail', name: 'admin_devis_detail_custom')]
    public function detail(int $id, DevisRepository $devisRepo): Response
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
}