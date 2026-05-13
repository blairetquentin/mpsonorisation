<?php

namespace App\Repository;

use App\Entity\Instruments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Instruments>
 */
class InstrumentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instruments::class);
    }
    public function FindBySearch(string $q):array
    {
        return $this->createQueryBuilder('m')
        ->where('m.libelle LIKE :q')
        ->setParameter('q', '%' . $q . '%')
        ->setMaxResults(8)
        ->getQuery()
        ->getResult();
    }
    
}
