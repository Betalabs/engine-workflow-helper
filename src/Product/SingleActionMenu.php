<?php


namespace Betalabs\EngineWorkflowHelper\Product;


use Betalabs\EngineWorkflowHelper\AbstractActionMenu;
use Betalabs\EngineWorkflowHelper\Enums\VirtualEntity;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;

class SingleActionMenu extends AbstractActionMenu
{
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'get';
    const EVENT_CLASS = 'App\Services\VirtualEntityRecord\ActionMenuSingle';
    const EVENT_METHOD = 'extra';

    /**
     * @var string
     */
    protected $entity = VirtualEntity::ITEM_SLUG;

    /**
     * @var string
     */
    private $endpointSuffix;

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
        $aliasIdEventParam = $this->searchParam('aliasId', $this->event->params);

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
                    'value' => $this->endpoint ?? 'products/',
                ],
                [
                    'engine_event_param_id' => $aliasIdEventParam->id,
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
