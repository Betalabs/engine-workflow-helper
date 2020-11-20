<?php

namespace Betalabs\EngineWorkflowHelper\Structure;


use Betalabs\EngineWorkflowHelper\AbstractWorkflow;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionApproach;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionOperator;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;

class Additional extends AbstractWorkflow
{
    const EVENT_CLASS = 'App\Structures\Form\Additional';
    const EVENT_METHOD = 'fetch';
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'get';

    /**
     * @var int
     */
    protected $engineRegistryId;
    /**
     * @var string
     */
    private $structureUri;
    /**
     * @var string
     */
    private $entity;

    /**
     * @param int $engineRegistryId
     * @return \Betalabs\EngineWorkflowHelper\Structure\Additional
     */
    public function setEngineRegistryId(int $engineRegistryId): Additional
    {
        $this->engineRegistryId = $engineRegistryId;
        return $this;
    }

    /**
     * @param string $structureUri
     * @return \Betalabs\EngineWorkflowHelper\Structure\Additional
     */
    public function setStructureUri(string $structureUri): Additional
    {
        $this->structureUri = $structureUri;
        return $this;
    }

    /**
     * @param string $entity
     * @return \Betalabs\EngineWorkflowHelper\Structure\Additional
     */
    public function setEntity(string $entity): Additional
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Create the workflow
     */
    public function create()
    {
        $this->setUp();
        $workflow = $this->createWorkflow();
        $eventParam = $this->searchParam('entity', $this->event->params);
        $this->conditionCreator
            ->setEngineEventParamId($eventParam->id)
            ->setWorkflowId($workflow->id)
            ->setValue($this->entity)
            ->setOperator(WorkflowConditionOperator::EQUAL)
            ->setApproach(WorkflowConditionApproach:: AND)
            ->create();

        $appRegistryParam = $this->searchParam('appRegistryId', $this->listener->params);
        $appUriParam = $this->searchParam('uri', $this->listener->params);
        $step = $this->stepCreator
            ->setApproach(WorkflowStepApproach::SYNCHRONOUS)
            ->setWorkflowId($workflow->id)
            ->setListenerId($this->listener->id)
            ->setParams([
                [
                    'engine_listener_param_id' => $appRegistryParam->id,
                    'value' => $this->engineRegistryId,
                ],
                [
                    'engine_listener_param_id' => $appUriParam->id,
                    'value' => $this->structureUri,
                ],
            ])
            ->create();

        $this->workflowUpdater
            ->setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->update();
    }
}
