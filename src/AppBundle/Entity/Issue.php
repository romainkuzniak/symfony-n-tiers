<?php

namespace AppBundle\Entity;

use Carbon\Carbon;

/**
 * @author Romain Kuzniak <romain.kuzniak@turn-it-up.org>
 */
class Issue
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $doneAt;

    /**
     * @var \DateTime
     */
    protected $closedAt;

    /**
     * @var Sprint
     */
    protected $sprint;

    public function __construct()
    {
        $this->createdAt = new \DateTime(Carbon::now()->toTimeString());
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getDoneAt()
    {
        return $this->doneAt;
    }

    public function setClosedAt(\DateTime $closedAt)
    {
        $this->closedAt = $closedAt;
    }

    /**
     * @return \DateTime
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }
}
