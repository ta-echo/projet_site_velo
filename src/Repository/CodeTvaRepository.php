<?php

namespace App\Repository;

use App\Entity\CodeTva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CodeTva|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeTva|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeTva[]    findAll()
 * @method CodeTva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeTvaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeTva::class);
    }

    // /**
    //  * @return CodeTva[] Returns an array of CodeTva objects
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
    public function findOneBySomeField($value): ?CodeTva
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
