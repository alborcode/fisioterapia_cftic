<?php

namespace App\Repository;

use App\Entity\Facultativos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facultativos>
 *
 * @method Facultativos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facultativos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facultativos[]    findAll()
 * @method Facultativos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacultativosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facultativos::class);
    }

    public function add(Facultativos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Facultativos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Añado busqueda por Especialidad devolviendo solo nombre y apellidos
    public function BuscarEspecialidad($especialidad)
    {
        return $this->getEntityManager()
            ->createQuery(
                '
                SELECT facultativos.especialidad, facultativos.nombre, facultativos.apellido1, facultativos.apellido2
                FROM App\Facultativos facultativos
                WHERE facultativos.especialidad =:especialidad
            '
            )
            ->setParameter('especialidad', $especialidad)
            ->getResult();
    }

    //    /**
    //     * @return Facultativos[] Returns an array of Facultativos objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Facultativos
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
