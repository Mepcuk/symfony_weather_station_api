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

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function averageTemperature($dateFilter)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT AVG(w.temperature) 
            FROM App\Entity\WeatherHourlyRecords w
            WHERE w.measure_at >= :parameterDate'
        )->setParameter('parameterDate', $dateFilter);

        return $query->getOneOrNullResult();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function averageHumidity($dateFilter)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT AVG(w.humidity) 
            FROM App\Entity\WeatherHourlyRecords w
            WHERE w.measure_at >= :parameterDate'
        )->setParameter('parameterDate', $dateFilter);

        return $query->getOneOrNullResult();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function averageWind($dateFilter)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT AVG(w.wind) 
            FROM App\Entity\WeatherHourlyRecords w
            WHERE w.measure_at >= :parameterDate'
        )->setParameter('parameterDate', $dateFilter);

        return $query->getOneOrNullResult();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function lastUpdateDatetime()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT MAX(w.measure_at) 
            FROM App\Entity\WeatherHourlyRecords w'
        );

        return $query->getOneOrNullResult();
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
