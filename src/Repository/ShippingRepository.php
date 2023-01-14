<?php

namespace App\Repository;

use App\Entity\Proprietaire;
use App\Entity\Shipping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shipping>
 *
 * @method Shipping|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shipping|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shipping[]    findAll()
 * @method Shipping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShippingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shipping::class);
    }

    public function save(Shipping $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Shipping $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Shipping[] Returns an array of Shipping objects
     */
    public function findByPropretaire($value): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('f')
            ->from(Shipping::class, 'f')
            ->leftJoin('f.place','place')
            ->addOrderBy('f.id', 'DESC')
        ;
        $qb->andWhere($qb->expr()->in('place.id',$value));
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $value
     * @return Shipping[] Returns an array of Shipping objects
     */
    public function findByLastPropretaire($value): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('f')
            ->from(Shipping::class, 'f')
            ->leftJoin('f.place','place')
            ->addOrderBy('f.id', 'DESC')
        ;
       $qb->andWhere($qb->expr()->in('place.id',$value));
        $qb->setMaxResults(10);
        return $qb->getQuery()->getResult();
    }

//    public function findOneBySomeField($value): ?Shipping
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
