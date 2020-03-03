<?php

namespace App\Repository;

use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\MakerBundle\Str;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Topic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topic[]    findAll()
 * @method Topic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    /**
     * @param string $titre
     *
     * @param string $pseudo
     *
     * @return mixed
     */
    public function findTopicByTitreAndAuteur(string $titre, string $pseudo)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->join('t.utilisateurId', 'u')
            ->where('t.titre LIKE :titre')
            ->setParameter('titre', '%'.$titre.'%');

        if ($pseudo) {
            $queryBuilder->andWhere('u.pseudo LIKE :auteur')
                ->setParameter('auteur', '%'.$pseudo.'%');
        }

        $result = $queryBuilder->getQuery()->getResult();

        return $result;
    }


    public function findAllWithPagination(int $page,int $nbMaxParPage)
    {

        $queryBuilder = $this->createQueryBuilder('t');
//                        ->orderBy('id');

        $query = $queryBuilder->getQuery();

        $premierResultat = ($page - 1) * $nbMaxParPage;

        $query->setFirstResult($premierResultat)->setMaxResults($nbMaxParPage);

        $paginator = new Paginator($query);

        if ( ($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas.'); // page 404, sauf pour la première page
        }

        return $paginator;

    }

    // /**
    //  * @return Topic[] Returns an array of Topic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Topic
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
