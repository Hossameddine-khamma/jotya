<?php

namespace App\Repository;

use App\Entity\Messages;
use App\Entity\Utilisateurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messages::class);
    }

    /**
    * @return Messages[] Returns an array of Messages objects
    */
    public function findMessagesEnvoyer($Expiditeur)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.expiditeur = :val')
            ->setParameter('val', $Expiditeur)
            ->orderBy('m.Date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Messages[] Returns an array of Messages objects
    */
    public function findMessagesRecu($destinataire)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.destinataire = :val')
            ->setParameter('val', $destinataire)
            ->orderBy('m.Date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Messages[] Returns an array of Messages objects
    */
    public function messagesParUtilisateur($admin){
        $messagesRecu=$this->findMessagesRecu($admin);

        $utilisateurs=$this->getEntityManager()->getRepository(Utilisateurs::class)->findAll();

        $messagesParUtilisateur=array();

        foreach($utilisateurs as $utilisateur){
            
            $messagesEnvoyer=$this->findMessagesRecu($utilisateur);
            $messagesUtilisateur= array();
            $messagesRecuDeUtilisateur= array();
            $id=$utilisateur->getId();
            foreach($messagesRecu as $messageRecu){
                if($messageRecu->getExpiditeur()->getId() == $id){
                    array_push($messagesRecuDeUtilisateur,$messageRecu);
                }
            }
            $messagesUtilisateur["recu"]=$messagesRecuDeUtilisateur;
            $messagesUtilisateur["envoyer"]=$messagesEnvoyer;
            if($messagesUtilisateur["recu"] && $messagesUtilisateur["envoyer"]){
                array_push($messagesParUtilisateur,$messagesUtilisateur);
            }
        }

        return $messagesParUtilisateur;
    }
    /*
    public function findOneBySomeField($value): ?Messages
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
