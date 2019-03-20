<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Enums\VirtualEntity;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionApproach;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionOperator;
use Betalabs\EngineWorkflowHelper\WorkflowSender;

abstract class AbstractActionMenu extends WorkflowSender
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
     * @var int
     */
    protected $engineRegistryId;

    /**
     * @param string $name
     * @return \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractActionMenu
     */
    public function setName(string $name): AbstractActionMenu
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $identification
     * @return \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractActionMenu
     */
    public function setIdentification(string $identification): AbstractActionMenu
    {
        $this->identification = $identification;
        return $this;
    }

    /**
     * @param int $engineRegistryId
     * @return \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractActionMenu
     */
    public function setEngineRegistryId(int $engineRegistryId): AbstractActionMenu
    {
        $this->engineRegistryId = $engineRegistryId;
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

    /**
     * @param $eventParam
     * @param $workflow
     */
    protected function createCondition($eventParam, $workflow): void
    {
        $this->conditionCreator
            ->setEngineEventParamId($eventParam->id)
            ->setWorkflowId($workflow->id)
            ->setValue(VirtualEntity::ITEMS_SLUG)
            ->setOperator(WorkflowConditionOperator::EQUAL)
            ->setApproach(WorkflowConditionApproach:: AND)
            ->create();
    }
}