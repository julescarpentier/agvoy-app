<?php

namespace App\Repository;

use App\Entity\Indisponibilite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Indisponibilite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Indisponibilite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Indisponibilite[]    findAll()
 * @method Indisponibilite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndisponibiliteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indisponibilite::class);
    }

    // /**
    //  * @return Indisponibilite[] Returns an array of Indisponibilite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Indisponibilite
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
