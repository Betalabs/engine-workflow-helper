<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractActionMenu;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;

class SingleActionMenu extends AbstractActionMenu
{
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'get';
    const EVENT_CLASS = 'App\Services\MenuAction\Service';
    const EVENT_METHOD = 'singleExtra';

    /**
     * @var string
     */
    protected $entity = 'order';
    /**
     * @var string
     */
    private $endpointSuffix;

    /**
     * Create the workflow
     *
     */
    public function create()
    {
        $this->setUp();

        $workflow = $this->createWorkflow();
        $eventParam = $this->searchParam('entity', $this->event->params);
        $this->createCondition($eventParam, $workflow);

        $appRegistryParam = $this->searchParam('appRegistryId', $this->listener->params);
        $appUriParam = $this->searchParam('uri', $this->listener->params);
        $idEventParam = $this->searchParam('id', $this->event->params);

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
                    'value' => $this->endpoint ?? 'orders/',
                ],
                [
                    'engine_event_param_id' => $idEventParam->id,
                    'engine_listener_param_id' => $appUriParam->id,
                ],
                [
                    'engine_listener_param_id' => $appUriParam->id,
                    'value' => $this->endpointSuffix ?? '/action-menu',
                ],
            ])
            ->create();

        $this->workflowUpdater
            ->setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->update();
    }

    /**
     * @param string $endpointSuffix
     * @return SingleActionMenu
     */
    public function setEndpointSuffix(string $endpointSuffix): SingleActionMenu
    {
        $this->endpointSuffix = $endpointSuffix;
        return $this;
    }
}