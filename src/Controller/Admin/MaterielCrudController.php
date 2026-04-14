<?php

namespace App\Controller\Admin;

use App\Entity\Materiel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MaterielCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Materiel::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(),
        TextField::new('libelle'),
        TextField::new('reference'),
        IntegerField::new('stock_dispo', 'Stock disponible'),
        IntegerField::new('stock_total', 'Stock total'),
        TextField::new('url_materiel', 'URL image')->hideOnIndex(),
        AssociationField::new('sous_categorie', 'Sous-catégorie'),
    ];
}
}
