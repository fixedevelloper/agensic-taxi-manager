<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 *
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function save(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Notification[] Returns an array of Notification objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
    public function findOneByLastUser($user):Notification
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.userid = :user')
            ->orWhere('s.alldriver = 1')
            ->setParameter('user',$user)
            ->setMaxResults(1)
            ->orderBy('s.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    public function findOneByLastCustomer($user):Notification
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.userid = :user')
            ->orWhere('s.allcustomer = 1')
            ->setParameter('user',$user)
            ->setMaxResults(1)
            ->orderBy('s.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    public function findOneByLastPropretaire($user):Notification
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.propretaire = :user')
            ->setParameter('user',$user)
            ->setMaxResults(1)
            ->orderBy('s.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
