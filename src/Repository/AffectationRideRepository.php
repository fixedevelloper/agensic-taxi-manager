<?php

namespace App\Repository;

use App\Entity\AffectationRide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AffectationRide>
 *
 * @method AffectationRide|null find($id, $lockMode = null, $lockVersion = null)
 * @method AffectationRide|null findOneBy(array $criteria, array $orderBy = null)
 * @method AffectationRide[]    findAll()
 * @method AffectationRide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffectationRideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AffectationRide::class);
    }

    public function save(AffectationRide $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AffectationRide $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findOneByLastDriver($user):AffectationRide
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.driver = :user')
            ->andWhere('s.isEnable = 1')
            ->setParameter('user',$user)
            ->setMaxResults(1)
            ->orderBy('s.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

}
