<?php

namespace AppBundle\Entity;

/**
 * @author Romain Kuzniak <romain.kuzniak@turn-it-up.org>
 */
class SprintStub1 extends Sprint
{
    const EXPECTED_CLOSED_AT = '2020-01-01';

    const ID = 1;

    const STATUS = 'OPEN';

    protected $id = self::ID;

    protected $status = self::STATUS;

    public function __construct()
    {
        parent::__construct();
        $this->addIssue(new IssueStub1());
        $this->addIssue(new IssueStub2());
        $this->expectedClosedAt = new \DateTime(self::EXPECTED_CLOSED_AT);
    }

}
