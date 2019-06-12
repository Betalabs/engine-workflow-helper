<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper;

use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;

abstract class AbstractMultipleActionMenu extends AbstractActionMenu
{
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'get';
    const EVENT_CLASS = 'App\Services\MenuAction\Service';
    const EVENT_METHOD = 'multipleExtra';

    /**
     * Create the workflow
     */
    public function create()
    {
        $this->setUp();

        $workflow = $this->createWorkflow();
        $eventParam = $this->searchParam('entity', $this->event->params);
        $this->createCondition($eventParam, $workflow);

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
                    'value' => $this->endpoint,
                ]
            ])
            ->create();

        $this->workflowUpdater
            ->setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->update();
    }
}