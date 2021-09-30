<?php

namespace App\Repository;

use App\Entity\OrderHeader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method OrderHeader|null find( $id, $lockMode = null, $lockVersion = null )
 * @method OrderHeader|null findOneBy( array $criteria, array $orderBy = null )
 * @method OrderHeader[]    findAll()
 * @method OrderHeader[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class OrderHeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderHeader::class);
    }

    public function findWithDetails(Request $request)
    {
        $query = $this->createQueryBuilder('o')
            ->innerJoin('o.orderDetails', 'od')
            ->innerJoin('od.product', 'p')
            ->leftJoin('o.user', 'u')
            ->addSelect('od')
            ->addSelect('u')
            ->addSelect('p');

        if ($request->query->get('startedAt')) {
            $query->andWhere('o.createdAt > :startedAt')
                ->setParameter('startedAt', $request->query->get('startedAt'));
        }

        if ($request->query->get('endedAt')) {
            $query->andWhere('o.createdAt < :endedAt')
                ->setParameter('endedAt', $request->query->get('endedAt'));
        }


        return $query->getQuery()
            ->getResult();
    }


    // /**
    //  * @return OrderHeader[] Returns an array of OrderHeader objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o . exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o . id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderHeader
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o . exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
