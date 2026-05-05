<?php

namespace App\Controller\Admin;

use App\Entity\MaterielSuggere;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class MaterielSuggereCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MaterielSuggere::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IntegerField::new('quantite'),
            AssociationField::new('materiel', 'Materiel'),
            AssociationField::new('instrument', 'Instrument'),
        ];
    }
}
