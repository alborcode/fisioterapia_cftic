<?php

namespace App\Repository;

use App\Entity\Rehabilitaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rehabilitaciones>
 *
 * @method Rehabilitaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rehabilitaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rehabilitaciones[]    findAll()
 * @method Rehabilitaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RehabilitacionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rehabilitaciones::class);
    }

    public function add(Rehabilitaciones $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Rehabilitaciones $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Se añade funcion para recuperar ultima Sesion de Rehabilitacion de un Paciente
    public function findMaxSesionPaciente()
    {
        return $this->createQueryBuilder('s')
            ->select('MAX(s.idrehabilitacion)')
            ->andWhere('s.idpaciente = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // Se añade funcion para recuperar Todas las Sesiones de Rehabilitacion de un Paciente
    public function findAllSesionesPaciente()
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.idpaciente = :val')
            ->setParameter('val', $value)
            ->orderBy('r.idrehabilitacion', 'DESC')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Rehabilitaciones[] Returns an array of Rehabilitaciones objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Rehabilitaciones
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
