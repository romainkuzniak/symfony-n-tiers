<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @author Romain Kuzniak <romain.kuzniak@turn-it-up.org>
 */
class Sprint
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $status;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $expectedClosedAt;

    /**
     * @var \DateTime
     */
    protected $effectiveClosedAt;

    /**
     * @var Collection
     */
    protected $issues;

    public function __construct()
    {
        $this->issues = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getExpectedClosedAt()
    {
        return $this->expectedClosedAt;
    }

    public function setEffectiveClosedAt(\DateTime $closedAt)
    {
        $this->effectiveClosedAt = $closedAt;
    }

    /**
     * @return \DateTime
     */
    public function getEffectiveClosedAt()
    {
        return $this->effectiveClosedAt;
    }

    /**
     * @param Issue $issues
     */
    public function addIssue(Issue $issues)
    {
        $this->issues[] = $issues;
    }

    public function removeIssue(Issue $issues)
    {
        $this->issues->removeElement($issues);
    }

    /**
     * @return Collection
     */
    public function getIssues()
    {
        return $this->issues;
    }
}
