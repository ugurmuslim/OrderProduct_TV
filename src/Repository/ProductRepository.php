<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find( $id, $lockMode = null, $lockVersion = null )
 * @method Product|null findOneBy( array $criteria, array $orderBy = null )
 * @method Product[]    findAll()
 * @method Product[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findWhereIn($ids)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id IN (:ids)')
            ->andWhere('p.status = (:status)')
            ->setParameter('ids', $ids)
            ->setParameter('status', true)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
