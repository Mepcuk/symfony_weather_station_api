<?php

namespace App\Repository;

use App\Entity\ApiLogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiLogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiLogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiLogs[]    findAll()
 * @method ApiLogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiLogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiLogs::class);
    }

    // /**
    //  * @return ApiLogs[] Returns an array of ApiLogs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiLogs
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
