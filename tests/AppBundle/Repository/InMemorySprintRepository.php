<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sprint;
use AppBundle\Exception\SprintNotFoundException;

/**
 * @author Romain Kuzniak <romain.kuzniak@turn-it-up.org>
 */
class InMemorySprintRepository extends SprintRepository
{

    /**
     * @var Sprint[]
     */
    public static $sprints = array();

    public function __construct()
    {
    }

    /**
     * @return Sprint
     * @throws SprintNotFoundException
     */
    public function find($id)
    {
        if (isset(self::$sprints[$id])) {
            return self::$sprints[$id];
        }
        throw new SprintNotFoundException();
    }

    /**
     * @return Sprint
     * @throws SprintNotFoundException
     */
    public function findSprintToClose()
    {
        foreach (self::$sprints as $sprint) {
            if ('CLOSE' !== $sprint->getStatus()) {
                return $sprint;
            }
        }
        throw new SprintNotFoundException();
    }

    public function findAverageClosedIssues()
    {
        $sprintsCount = 0;
        $issuesCount = 0;
        foreach (self::$sprints as $sprint) {
            $sprintsCount++;
            $issuesCount += $sprint->getIssues()->count();
        }

        return $sprintsCount !== 0 ? $issuesCount / $sprintsCount : 0;
    }

}
