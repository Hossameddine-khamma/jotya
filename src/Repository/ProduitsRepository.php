<?php

namespace App\Repository;

use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }

    public function findSimilarTo($produit)
    {
       $produitsSameBudget= $this->createQueryBuilder('e')
        ->Where('e.Budget = :produitBudget')
        ->setParameter(
                'produitBudget' , $produit->getBudget()->getId()
        )
        ->getQuery()
        ->getResult();
        $produitsSameStyles=Array();
        foreach($produitsSameBudget as $produitSameBudget){
           if($produitSameBudget->getStyles()[0]->getId()== $produit->getStyles()[0]->getId()&& $produitSameBudget->getId()!=$produit->getId() ){
               $produitsSameStyles[]=$produitSameBudget;
           }
        }
        $produitsSameGenre=Array();
        foreach($produitsSameStyles as $produitSameStyles){
            if($produitSameStyles->getGenre()->getId()== $produit->getGenre()->getId()){
                $produitsSameGenre[]=$produitSameStyles;
            }
         }
         dump($produitsSameGenre);
        $produitsSameType=Array();
        foreach($produitsSameGenre as $produitSameType){
            if($produitSameType->getType()->getId()== $produit->getType()->getId()){
                $produitsSameType[]=$produitSameType;
            }
         }
         dump($produitsSameType);
        return $produitsSameType
        ;
    }

    // /**
    //  * @return Produits[] Returns an array of Produits objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produits
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
