<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Traits\SetEventByName;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;
use Illuminate\Support\Collection;

abstract class AbstractAppDispatcherWorkflow extends AbstractWorkflow
{
    use SetEventByName;

    const EVENT_CLASS = '';
    const EVENT_METHOD = '';
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const EVENT_NAME = null;

    /**
     * @var string
     */
    protected $eventName;
    /**
     * @var \Illuminate\Support\Collection;
     */
    protected $eventParams;
    /**
     * @var mixed
     */
    protected $appRegistryParam;
    /**
     * @var mixed
     */
    protected $appUriParam;
    /**
     * @var \Illuminate\Support\Collection;
     */
    protected $listenerParams;
    /**
     * @var string
     */
    protected $approach = WorkflowStepApproach::SYNCHRONOUS;
    /**
     * @var int
     */
    protected $engineRegistryId;
    /**
     * @var string
     */
    protected $appEndpoint;

    /**
     * Create the workflow
     *
     */
    public function create()
    {
        $this->setUp();

        $workflow = $this->createWorkflow();

        $this->listenerParams = $this->listenerParams()
            ->mapWithKeys(function(string $listener){
                return [$listener => $this->searchParam($listener, $this->listener->params)];
            });

        $this->eventParams = $this->eventParams()
            ->mapWithKeys(function(string $event){
                return [$event => $this->searchParam($event, $this->event->params)];
            });

        $stepParams = $this->createStepParams();

        $step = $this->stepCreator
            ->setWorkflowId($workflow->id)
            ->setListenerId($this->listener->id)
            ->setApproach($this->approach)
            ->setParams($stepParams)
            ->create();

        $this->workflowUpdater
            ->setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->update();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function listenerParams(): Collection
    {
        return collect([
            'appRegistryId',
            'uri',
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected abstract function eventParams(): Collection;

    /**
     * @return array
     */
    protected abstract function createStepParams(): array;

    /**
     * @param string $eventName
     * @return \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractAppDispatcherWorkflow
     */
    public function setEventName(string $eventName): AbstractAppDispatcherWorkflow
    {
        $this->eventName = $eventName;
        return $this;
    }

    /**
     * @param string $approach
     * @return AbstractAppDispatcherWorkflow
     */
    public function setApproach(string $approach): AbstractAppDispatcherWorkflow
    {
        $this->approach = $approach;
        return $this;
    }

    /**
     * @param int $engineRegistryId
     * @return AbstractAppDispatcherWorkflow
     */
    public function setEngineRegistryId(int $engineRegistryId): AbstractAppDispatcherWorkflow
    {
        $this->engineRegistryId = $engineRegistryId;
        return $this;
    }

    /**
     * @param string $appEndpoint
     * @return AbstractAppDispatcherWorkflow
     */
    public function setAppEndpoint(string $appEndpoint): AbstractAppDispatcherWorkflow
    {
        $this->appEndpoint = $appEndpoint;
        return $this;
    }
}