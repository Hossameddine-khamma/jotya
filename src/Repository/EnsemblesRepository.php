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

    /**
    * @return Ensembles[] Returns an array of Ensembles objects
    */
    public function findSimilarTo($ensemble)
    {
       $ensemblesSameBudget= $this->createQueryBuilder('e')
        ->Where('e.Budget = :ensembleBudget')
        ->setParameter(
                'ensembleBudget' , $ensemble->getBudget()->getId()
        )
        ->getQuery()
        ->getResult();
        $ensemblesSameStyles=Array();
        foreach($ensemblesSameBudget as $ensembleSameBudget){
           if($ensembleSameBudget->getStyles()[0]->getId()== $ensemble->getStyles()[0]->getId()&& $ensembleSameBudget->getId()!=$ensemble->getId() ){
               $ensemblesSameStyles[]=$ensembleSameBudget;
           }
        }
        $ensemblesSameGenre=Array();
        foreach($ensemblesSameStyles as $ensembleSameStyles){
            if($ensembleSameStyles->getGenre()->getId()== $ensemble->getGenre()->getId()){
                $ensemblesSameGenre[]=$ensembleSameStyles;
            }
         }
        return $ensemblesSameGenre
        ;
    }

    public function getGender($gender, $genreRepository)
    {
       $ensembles= $this->createQueryBuilder('e')
        ->Where('e.Genre = :gender')
        ->setParameter(
            'gender' ,  $genreRepository->findOneBy(['description'=>$gender])
            
        )
        ->getQuery()
        ->getResult();
        
        return $ensembles
        ;
    }

    public function getStyleGender($gender, $style, $genreRepository, $stylesRepository)
    {
       $ensembles= $this->createQueryBuilder('e')
        ->Where('e.Genre = :gender')
        ->setParameter(
            'gender' , $genreRepository->findOneBy(['description'=>$gender])
            )
        ->getQuery()
        ->getResult();

        $ensemblesStyles=Array();
        foreach($ensembles as $ensemble){
            if($ensemble->getStyles()[0]->getId()== $style ){
                $ensemblesStyles[]=$ensemble;
            }
         }
        
        return $ensemblesStyles
        ;
    }

    public function getproduitsGender($gender, $Type, $genreRepository)
    {
       $ensembles= $this->createQueryBuilder('e')
        ->Where('e.Genre = :gender')
        ->setParameter(
            'gender' , $genreRepository->findOneBy(['description'=>$gender])
            )
        ->getQuery()
        ->getResult();

        $ensemblesType=Array();
        foreach($ensembles as $ensemble){
            $produits=$ensemble->getProduits();
            $Types=Array();
            foreach($produits as $produit){
                array_push($Types,$produit->gettype()->getId());
            }
            if( in_array($Type,$Types) ){
                $ensemblesType[]=$ensemble;
            }
         }
        
        return $ensemblesType
        ;
    }

    public function getBonPlan(){
        return $this->createQueryBuilder('e')
        ->andWhere('e.promotion is not null')
        ->getQuery()
        ->getResult()
    ;

    }

    public function getNouveau(){
        return $this->createQueryBuilder('e')
            ->orderBy('e.updatedAt', 'DESC')
            ->setMaxResults(80)
            ->getQuery()
            ->getResult()
        ;

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
