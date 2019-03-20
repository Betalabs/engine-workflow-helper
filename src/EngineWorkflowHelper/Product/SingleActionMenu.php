<?php


namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Product;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractActionMenu;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;

class SingleActionMenu extends AbstractActionMenu
{
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'get';
    const EVENT_CLASS = 'App\Services\VirtualEntityRecord\ActionMenuSingle';
    const EVENT_METHOD = 'extra';

    /**
     * Create the workflow
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

}