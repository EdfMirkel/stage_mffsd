<?php

namespace App\Repository;

use App\Entity\Currentapply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Currentapply|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currentapply|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currentapply[]    findAll()
 * @method Currentapply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrentapplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currentapply::class);
    }

    // /**
    //  * @return Currentapply[] Returns an array of Currentapply objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Currentapply
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
