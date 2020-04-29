<?php

namespace App\Repository;

use App\Entity\Reponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @method Reponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reponse[]    findAll()
 * @method Reponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponse::class);
    }

    // /**
    //  * @return Reponse[] Returns an array of Reponse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reponse
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByReponse(int $page, int $lenght){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('r')
            ->from('App\Entity\Reponse', 'r')
            ->join('r.id_question', 'q')
            ->where('r.id_question = q.id')
            ->setFirstResult(($page - 1)* $lenght)
            ->setMaxResults($lenght);
            //->andWhere('r.id_question = 1', 'q.id = 1');
       
        return $qb->getQuery()->getResult();
        }


        public function findByReponseExpected(int $page, int $lenght){
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('r')
                ->from('App\Entity\Reponse', 'r')
                ->join('r.reponse_expected', 'q')
                ->where('r.reponse_expected = q.id')
                ->setFirstResult(($page - 1)* $lenght)
                ->setMaxResults($lenght);
                //->andWhere('r.id_question = 1', 'q.id = 1');
           
            return $qb->getQuery()->getResult();
            }
}
