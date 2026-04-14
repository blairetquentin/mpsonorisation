<?php

namespace App\Controller\Admin;

use App\Entity\SousCategorie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SousCategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SousCategorie::class;
    }

   public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(),
        TextField::new('libelle'),
        AssociationField::new('categorie', 'Catégorie'),
    ];
}
}
