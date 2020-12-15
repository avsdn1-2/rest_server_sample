<?php

namespace App\Repository;

use App\Entity\Weather;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Weather|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weather|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weather[]    findAll()
 * @method Weather[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weather::class);
    }

    public function updateWeather($temperatur, $humidity, $windSpeed, $cityName)
    {
        $this->createQueryBuilder('wu')
            ->update(Weather::class)
            ->set('wu.temperature', $temperatur)
            ->set('wu.humidity', $humidity)
            ->set('wu.windSpeed', $windSpeed)
            ->where('wu.cityName = :city')
            ->setParameter('city', $cityName)
            ->getQuery()
            ->execute();
    }
    public function findByCityName(string $cityName): ?Weather
    {
        return $this->createQueryBuilder('w')
            ->where('w.cityName = :cityName')
            ->setParameter('cityName', $cityName)
            ->getQuery()
            ->getOneOrNullResult();

    }

    // /**
    //  * @return Weather[] Returns an array of Weather objects
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
    public function findOneBySomeField($value): ?Weather
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
