<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    // /**
    //  * @return Question[] Returns an array of Question objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Question
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /*public function findByQuestion($question)
    {
        $qb = $this->createQueryBuilder('c')
            ->Join('c.id_categorie', 's')
            //->addSelect('s')
            ->where('s.id_categorie IN (:id_categorie)')
            ->setParameter('id_categorie', $question);
        //dump($qb);

        return $qb->getQuery()->getResult();
    }*/

    public function findByQuestion($question)
    {
        $qb = $this->createQueryBuilder('c')
        ->select('c')
            ->leftJoin('App\Repository\CategorieRepository', 'd','WITH', 'c.name = d.name AND c.score < d.score')
            ->where( 'd.score IS NULL' )
            ->orderBy( 'c.name','DESC' );
        return $qb->getQuery()->getResult();
    }
}

