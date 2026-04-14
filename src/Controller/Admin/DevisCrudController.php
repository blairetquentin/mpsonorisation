<?php

namespace App\Controller\Admin;

use App\Entity\Devis;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;


class DevisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Devis::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $voirDetail = Action::new('voirDetail', 'Voir détail', 'fa fa-eye')
            ->linkToRoute('admin_devis_detail_custom', fn(Devis $devis) => ['id' => $devis->getId()]);

        return $actions
            ->add(Crud::PAGE_INDEX, $voirDetail)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateTimeField::new('date_demande', 'Date de demande'),
            ChoiceField::new('statut')->setChoices([
                'En attente' => 'en_attente',
                'Validé' => 'valide',
                'Refusé' => 'refuse',
            ]),
            TextareaField::new('commentaire_admin', 'Commentaire admin')->hideOnIndex(),
            AssociationField::new('panier', 'Panier'),
        ];
    }
}
