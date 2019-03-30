<?php

namespace App\Repository;

use App\Entity\CashUp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CashUp|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashUp|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashUp[]    findAll()
 * @method CashUp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashUpRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CashUp::class);
    }

//    /**
//     * @return CashUp[] Returns an array of CashUp objects
//     */
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
    public function findOneBySomeField($value): ?CashUp
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
