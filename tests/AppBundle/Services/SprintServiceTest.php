<?php

namespace AppBundle\Services;

use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueStub1;
use AppBundle\Entity\IssueStub2;
use AppBundle\Entity\SprintStub1;
use AppBundle\Repository\InMemorySprintRepository;
use Carbon\Carbon;

/**
 * @author Romain Kuzniak <romain.kuzniak@turn-it-up.org>
 */
class SprintServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SprintService
     */
    private $service;

    /**
     * @test
     * @expectedException \AppBundle\Exception\SprintNotFoundException
     */
    public function NonExistingSprint_Close_ThrowException()
    {
        InMemorySprintRepository::$sprints = array();
        $this->service->closeSprint(SprintStub1::ID);
    }

    /**
     * @test
     * @expectedException \AppBundle\Exception\SprintAlreadyClosedException
     */
    public function AlreadyClosedSprint_Close_ThrowException()
    {
        $sprint = new SprintStub1();
        $sprint->setStatus('CLOSE');

        InMemorySprintRepository::$sprints = array(SprintStub1::ID => $sprint);
        $this->service->closeSprint(SprintStub1::ID);
    }

    /**
     * @test
     */
    public function CloseSprint()
    {
        $report = $this->service->closeSprint(SprintStub1::ID);
        $actualSprint = InMemorySprintRepository::$sprints[SprintStub1::ID];
        $this->assertEquals(1, $report['closedIssuesCount']);
        $this->assertEquals(1, $report['averageClosedIssues']);
        $this->assertEquals('CLOSE', $actualSprint->getStatus());
        $this->assertEquals(new \DateTime(Carbon::now()->toTimeString()), $actualSprint->getEffectiveClosedAt());
        $this->assertCount(1, $actualSprint->getIssues());
    }

    /**
     * @test
     */
    public function CloseExpectedSprint()
    {
        $sprintId = $this->service->closeExpectedSprint();
        $this->assertEquals(SprintStub1::ID, $sprintId);
        $actualSprint = InMemorySprintRepository::$sprints[SprintStub1::ID];
        $this->assertEquals('CLOSE', $actualSprint->getStatus());
        $this->assertEquals(new \DateTime(Carbon::now()->toTimeString()), $actualSprint->getEffectiveClosedAt());
        $this->assertCount(1, $actualSprint->getIssues());
    }

    /**
     * @test
     * @expectedException \AppBundle\Exception\SprintNotFoundException
     */
    public function NonExistingSprint_Get_ThrowException()
    {
        InMemorySprintRepository::$sprints = array();
        $this->service->get(SprintStub1::ID);
    }

    /**
     * @test
     */
    public function Get()
    {
        $sprint = $this->service->get(SprintStub1::ID);
        $this->assertEquals(new \DateTime(Carbon::now()->toTimeString()), $sprint->getCreatedAt());
        $this->assertNull($sprint->getEffectiveClosedAt());
        $this->assertEquals(new \DateTime(SprintStub1::EXPECTED_CLOSED_AT), $sprint->getExpectedClosedAt());
        $this->assertEquals(SprintStub1::ID, $sprint->getId());
        $this->assertEquals('OPEN', $sprint->getStatus());
        $i = 0;
        foreach ($sprint->getIssues() as $issue) {
            $stub = 'AppBundle\Entity\IssueStub' . ++$i;
            $this->assertIssue($stub, $issue);
        }
    }

    private function assertIssue($stub, Issue $issue)
    {
        /** @var IssueStub1 | IssueStub2 $stub */
        $this->assertNull($issue->getClosedAt());
        $this->assertEquals(new \DateTime(Carbon::now()->toTimeString()), $issue->getCreatedAt());
        $this->assertEquals($stub::DESCRIPTION, $issue->getDescription());
        if (null !== $stub::DONE_AT) {
            $this->assertEquals(new \DateTime($stub::DONE_AT), $issue->getDoneAt());
        }
        $this->assertEquals($stub::ID, $issue->getId());
        $this->assertEquals($stub::STATUS, $issue->getStatus());
        $this->assertEquals($stub::TITLE, $issue->getTitle());
    }

    protected function setUp()
    {
        Carbon::setTestNow(Carbon::now());
        $this->service = new SprintService();
        $this->service->setSprintRepository(new InMemorySprintRepository());
        InMemorySprintRepository::$sprints = array(SprintStub1::ID => new SprintStub1());
    }

    protected function tearDown()
    {
        Carbon::setTestNow();
    }

}
