<?php

namespace App\Repository;

use App\Entity\Instruments;
use App\Entity\MaterielSuggere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class MaterielSuggereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterielSuggere::class);
    }
    public function findByScene(int $sceneId, int $instrumentsId): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.scene = :sceneId')
            ->setParameter('sceneId', $sceneId)
            ->andWhere('m.instruments = :instrumentsId')
            ->setParameter('instrumentsId', $instrumentsId)
            ->getQuery()
            ->getResult();
    }

}
