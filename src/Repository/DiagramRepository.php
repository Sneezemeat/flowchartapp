<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Diagram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Diagram|null find($id, $lockMode = null, $lockVersion = null)
 * @method Diagram|null findOneBy(array $criteria, array $orderBy = null)
 * @method Diagram[]    findAll()
 * @method Diagram[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiagramRepository extends ServiceEntityRepository implements DiagramRepositoryInterface
{
    protected UserRepository       $userRepository;

    public function __construct(ManagerRegistry $registry, UserRepository $userRepository)
    {
        parent::__construct($registry, Diagram::class);
        $this->userRepository = $userRepository;
    }

    /**
     * persist a diagram to the db.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function persistDiagram(Diagram $diagram): Diagram
    {
        $this->_em->persist($diagram);
        $this->_em->flush();

        return $diagram;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function removeDiagram(Diagram $diagram): void
    {
        $this->_em->remove($diagram);
        $this->_em->flush();
    }

    /**
     * Return the count of all diagrams in the db.
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getDiagramCount(string $userId): int
    {
        $qb = $this->createQueryBuilder('d');

        return (int) ($qb->select('count(d.id)')->where('d.user = :u')->setParameter('u', $userId)->getQuery()->getSingleScalarResult());
    }
}
