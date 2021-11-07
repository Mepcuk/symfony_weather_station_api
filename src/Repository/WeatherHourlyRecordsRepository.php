<?php

namespace App\Repository;

use App\Entity\WeatherHourlyRecords;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WeatherHourlyRecords|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeatherHourlyRecords|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeatherHourlyRecords[]    findAll()
 * @method WeatherHourlyRecords[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherHourlyRecordsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeatherHourlyRecords::class);
    }

    // /**
    //  * @return WeatherHourlyRecords[] Returns an array of WeatherHourlyRecords objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WeatherHourlyRecords
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
