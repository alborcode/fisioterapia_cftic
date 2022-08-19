<?php

namespace App\Repository;

use App\Entity\Informes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Informes>
 *
 * @method Informes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Informes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Informes[]    findAll()
 * @method Informes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InformesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Informes::class);
    }

    public function add(Informes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Informes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Se añade funcion para hacer Join de Informes de Pacientes
    public function findAllInformesPaciente()
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.informes', 'p')
            ->join('Informes', 'Pacientes')
            //->addSelect('cabinet')
            //->andWhere('g.fechaAbonoCliente IS NULL AND g.abonoClienteRetenido=0 AND g.resolucionIncidenciaCliente!=3 AND g.delegacion=u.delegacion')
            ->orderBy('g.idPaciente', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Informes[] Returns an array of Informes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Informes
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
