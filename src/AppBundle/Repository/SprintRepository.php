<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sprint;
use AppBundle\Exception\SprintNotFoundException;
use Carbon\Carbon;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * @author Romain Kuzniak <romain.kuzniak@turn-it-up.org>
 */
class SprintRepository extends EntityRepository
{

    /**
     * @return Sprint
     * @throws SprintNotFoundException
     */
    public function find($id)
    {
        $sprint = parent::find($id);

        if (null === $sprint) {
            throw new SprintNotFoundException();
        }

        return $sprint;
    }

    /**
     * @return Sprint
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findSprintToClose()
    {
        try {
            return $this->createQueryBuilder('s')
                ->andWhere('s.expectedClosedAt < :now')
                ->setParameter('now', new \DateTime(Carbon::now()->toDateTimeString()))
                ->andWhere('s.status != :status')
                ->setParameter('status', 'CLOSE')
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $nre) {
            throw new SprintNotFoundException();
        }
    }

    /**
     * @return int
     */
    public function findAverageClosedIssues()
    {
        return (int) $this->createQueryBuilder('s')
            ->select('AVG(i.id) as averageClosedIssues')
            ->leftJoin('s.issues', 'i')
            ->andWhere('s.status = :status')
            ->setParameter('status', 'CLOSE')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
