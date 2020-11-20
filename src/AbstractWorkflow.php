<?php

namespace Betalabs\EngineWorkflowHelper;


use Betalabs\EngineWorkflowHelper\WorkflowSender;

abstract class AbstractWorkflow extends WorkflowSender
{
    /**
     * @var string
     */
    protected $identification;
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     * @return \Betalabs\EngineWorkflowHelper\AbstractWorkflow
     */
    public function setName(string $name): AbstractWorkflow
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $identification
     * @return \Betalabs\EngineWorkflowHelper\AbstractWorkflow
     */
    public function setIdentification(string $identification): AbstractWorkflow
    {
        $this->identification = $identification;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function createWorkflow()
    {
        $workflow = $this->workflowCreator
            ->setName($this->name)
            ->setEngineEventId($this->event->id)
            ->setIdentification($this->identification)
            ->create();
        return $workflow;
    }
}
