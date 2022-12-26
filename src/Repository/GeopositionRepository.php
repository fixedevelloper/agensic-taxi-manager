<?php


namespace App\Repository;

use App\Entity\Geoposition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Geoposition>
 *
 * @method Geoposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Geoposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Geoposition[]    findAll()
 * @method Geoposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeopositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Geoposition::class);
    }
    //    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
}
