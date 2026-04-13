<?php

namespace App\Repository;

use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

    public function findAllWithRelations():array
    {
        return $this->createQueryBuilder('materiel')
        ->addSelect('sousCategorie','categorie')
        ->join('materiel.sous_categorie', 'sousCategorie')
        ->join('sousCategorie.categorie', 'categorie')
        ->orderBy('categorie.libelle','ASC')
        ->addOrderBy('sousCategorie.libelle', 'ASC')
        ->addOrderBy('materiel.libelle', 'ASC')
        ->getQuery()
        ->getResult();
    }
}
