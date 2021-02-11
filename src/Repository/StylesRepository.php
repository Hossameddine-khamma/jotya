<?php

namespace App\Repository;

use App\Entity\styles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Styles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Styles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Styles[]    findAll()
 * @method Styles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StylesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Styles::class);
    }

    // /**
    //  * @return Styles[] Returns an array of Styles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Styles
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
