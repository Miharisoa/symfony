<?php

namespace App\Repository;

use App\Entity\UtilisateurVuTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UtilisateurVuTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method UtilisateurVuTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method UtilisateurVuTopic[]    findAll()
 * @method UtilisateurVuTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurVuTopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UtilisateurVuTopic::class);
    }

    // /**
    //  * @return UtilisateurVuTopic[] Returns an array of UtilisateurVuTopic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UtilisateurVuTopic
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
