<?php

namespace App\Repository;

use App\Entity\Ensembles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ensembles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ensembles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ensembles[]    findAll()
 * @method Ensembles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnsemblesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ensembles::class);
    }

    // /**
    //  * @return Ensembles[] Returns an array of Ensembles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ensembles
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
