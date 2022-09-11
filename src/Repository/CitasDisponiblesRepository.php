<?php

namespace App\Repository;

use App\Entity\CitasDisponibles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CitasDisponibles>
 *
 * @method CitasDisponibles|null find($id, $lockMode = null, $lockVersion = null)
 * @method CitasDisponibles|null findOneBy(array $criteria, array $orderBy = null)
 * @method CitasDisponibles[]    findAll()
 * @method CitasDisponibles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CitasDisponiblesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CitasDisponibles::class);
    }

    public function add(CitasDisponibles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CitasDisponibles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CitasDisponibles[] Returns an array of CitasDisponibles objects
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

//    public function findOneBySomeField($value): ?CitasDisponibles
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
