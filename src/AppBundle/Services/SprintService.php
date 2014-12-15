<?php

namespace AppBundle\Services;

use AppBundle\Entity\Sprint;
use AppBundle\Exception\SprintAlreadyClosedException;
use AppBundle\Exception\SprintNotFoundException;
use AppBundle\Repository\SprintRepository;

/**
 * @author Romain Kuzniak <romain.kuzniak@turn-it-up.org>
 */
class SprintService
{

    /**
     * @var SprintRepository
     */
    private $sprintRepository;

    /**
     * @return array
     * @throws SprintNotFoundException
     * @throws SprintAlreadyClosedException
     */
    public function closeSprint($id)
    {
        /** @var Sprint $sprint */
        $sprint = $this->sprintRepository->find($id);

        if ('CLOSE' === $sprint->getStatus()) {
            throw new SprintAlreadyClosedException();
        }

        foreach ($sprint->getIssues() as $issue) {
            if ('DONE' === $issue->getStatus()) {
                $issue->setClosedAt(new \DateTime());
                $issue->setStatus('CLOSE');
            } else {
                $sprint->removeIssue($issue);
            }
        }
        $sprint->setEffectiveClosedAt(new \DateTime());
        $sprint->setStatus('CLOSE');

        $closedIssuesCount = $sprint->getIssues()->count();

        return array(
            'closedIssuesCount'   => $closedIssuesCount,
            'averageClosedIssues' => $this->sprintRepository->findAverageClosedIssues()
        );
    }

    /**
     * @return int
     * @throws SprintNotFoundException
     * @throws SprintAlreadyClosedException
     */
    public function closeExpectedSprint()
    {
        $sprint = $this->sprintRepository->findSprintToClose();

        foreach ($sprint->getIssues() as $issue) {
            if ('DONE' === $issue->getStatus()) {
                $issue->setClosedAt(new \DateTime());
                $issue->setStatus('CLOSE');
            } else {
                $sprint->removeIssue($issue);
            }
        }
        $sprint->setEffectiveClosedAt(new \DateTime());
        $sprint->setStatus('CLOSE');

        return $sprint->getId();
    }

    /**
     * @return Sprint
     * @throws \AppBundle\Exception\SprintNotFoundException
     */
    public function get($id)
    {
        return $this->sprintRepository->find($id);
    }

    public function setSprintRepository(SprintRepository $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
    }

}
