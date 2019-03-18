<?php


namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Product;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Enums\VirtualEntity;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionApproach;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionOperator;
use Betalabs\EngineWorkflowHelper\WorkflowSender;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;


class SingleActionMenu extends WorkflowSender
{
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'get';
    const EVENT_CLASS = 'App\Services\VirtualEntityRecord\ActionMenuSingle';
    const EVENT_METHOD = 'extra';

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $identification;
    /**
     * @var int
     */
    private $engineRegistryId;

    /**
     * @param string $name
     * @return SingleActionMenu
     */
    public function setName(string $name): SingleActionMenu
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $identification
     * @return SingleActionMenu
     */
    public function setIdentification(string $identification): SingleActionMenu
    {
        $this->identification = $identification;
        return $this;
    }

    /**
     * @param int $engineRegistryId
     * @return SingleActionMenu
     */
    public function setEngineRegistryId(int $engineRegistryId): SingleActionMenu
    {
        $this->engineRegistryId = $engineRegistryId;
        return $this;
    }

    /**
     * Create the workflow
     *
     */
    public function create()
    {
        $this->setUp();

        $workflow = $this->createWorkflow();
        $eventParam = $this->searchEventParam('entity', $this->event->params);
        $this->createCondition($eventParam, $workflow);

        $appRegistryParam = $this->searchEventParam('appRegistryId', $this->listener->params);
        $appUriParam = $this->searchEventParam('uri', $this->listener->params);
        $aliasIdEventParam = $this->searchEventParam('aliasId', $this->event->params);

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
                    'value' => 'products/',
                ],
                [
                    'engine_event_param_id' => $aliasIdEventParam->id,
                    'engine_listener_param_id' => $appUriParam->id,
                ],
                [
                    'engine_listener_param_id' => $appUriParam->id,
                    'value' => '/action-menu',
                ],
            ])
            ->create();

        $this->workflowUpdater
            ->setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->update();

    }

    /**
     * @return mixed
     */
    private function createWorkflow()
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
    private function createCondition($eventParam, $workflow): void
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