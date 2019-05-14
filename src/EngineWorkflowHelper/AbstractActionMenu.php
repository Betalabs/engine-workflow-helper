<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Enums\VirtualEntity;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionApproach;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionOperator;

abstract class AbstractActionMenu extends AbstractWorkflow
{
    /**
     * @var int
     */
    protected $engineRegistryId;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @param string $endpoint
     * @return AbstractActionMenu
     */
    public function setEndpoint(string $endpoint): AbstractActionMenu
    {
        $this->endpoint = $endpoint;
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