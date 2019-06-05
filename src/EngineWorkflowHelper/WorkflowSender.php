<?php

namespace Betalabs\EngineWorkflowHelper;

use Betalabs\EngineWorkflowHelper\Event\Indexer as EventIndexer;
use Betalabs\EngineWorkflowHelper\Listener\Indexer as ListenerIndexer;
use Betalabs\EngineWorkflowHelper\Workflow\Updater as WorkflowUpdater;
use Betalabs\EngineWorkflowHelper\Workflow\Creator as WorkflowCreator;
use Betalabs\EngineWorkflowHelper\Workflow\Condition\Creator as ConditionCreator;
use Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;

abstract class WorkflowSender
{
    /**
     * @var \Betalabs\EngineWorkflowHelper\Event\Indexer
     */
    protected $eventIndexer;
    /**
     * @var \Betalabs\EngineWorkflowHelper\Listener\Indexer
     */
    protected $listenerIndexer;
    /**
     * @var \Betalabs\EngineWorkflowHelper\Workflow\Updater
     */
    protected $workflowUpdater;
    /**
     * @var \Betalabs\EngineWorkflowHelper\Workflow\Creator
     */
    protected $workflowCreator;
    /**
     * @var \Betalabs\EngineWorkflowHelper\Workflow\Condition\Creator
     */
    protected $conditionCreator;

    /**
     * @var \Betalabs\EngineWorkflowHelper\Workflow\Step\Creator
     */
    protected $stepCreator;
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $event;
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $listener;
    /**
     * @const string
     */
    const EVENT_CLASS = self::EVENT_CLASS;
    /**
     * @const string
     */
    const EVENT_METHOD = self::EVENT_METHOD;
    /**
     * @const string
     */
    const LISTENER_CLASS = self::LISTENER_CLASS;
    /**
     * @const string
     */
    const LISTENER_METHOD = self::LISTENER_METHOD;


    /**
     * Creator constructor.
     *
     * @param \Betalabs\EngineWorkflowHelper\Event\Indexer $eventIndexer
     * @param \Betalabs\EngineWorkflowHelper\Listener\Indexer $listenerIndexer
     * @param \Betalabs\EngineWorkflowHelper\Workflow\Updater $workflowUpdater
     * @param \Betalabs\EngineWorkflowHelper\Workflow\Creator $workflowCreator
     * @param \Betalabs\EngineWorkflowHelper\Workflow\Condition\Creator $conditionCreator
     * @param \Betalabs\EngineWorkflowHelper\Workflow\Step\Creator $stepCreator
     */
    public function __construct(
        EventIndexer $eventIndexer,
        ListenerIndexer $listenerIndexer,
        WorkflowUpdater $workflowUpdater,
        WorkflowCreator $workflowCreator,
        ConditionCreator $conditionCreator,
        StepCreator $stepCreator
    ) {
        $this->eventIndexer = $eventIndexer;
        $this->listenerIndexer = $listenerIndexer;
        $this->workflowUpdater = $workflowUpdater;
        $this->workflowCreator = $workflowCreator;
        $this->conditionCreator = $conditionCreator;
        $this->stepCreator = $stepCreator;
    }

    /**
     * Set Workflow events and listeners
     *
     */
    protected function setUp()
    {
        $this->setEvent();
        $this->setListener();
    }

    /**
     * Create the workflow
     *
     * @return mixed
     */
    public abstract function create();

    /**
     * Search Workflow Event Param by name
     *
     * @param string $name
     * @param array $params
     *
     * @return \stdClass|null
     */
    protected function searchParam(string $name, array $params)
    {
        foreach ($params as $key => $param) {
            if ($param->name == $name) {
                return $params[$key];
            }
        }

        return null;
    }


    /**
     * Set event property
     */
    protected function setEvent()
    {
        $events = $this->eventIndexer
            ->setQuery([
                'class' => static::EVENT_CLASS ?? $this->eventClass,
                'method' => static::EVENT_METHOD ?? $this->eventMethod,
                '_with' => 'params',
            ])
            ->setLimit(1)
            ->retrieve();

        if ($events->isEmpty()) {
            throw new \RuntimeException('Engine Events not found.');
        }
        $this->event = $events->first();
    }

    /**
     * Set listener property
     */
    protected function setListener()
    {
        $listeners = $this->listenerIndexer
            ->setQuery([
                'class' => static::LISTENER_CLASS ?? $this->listenerClass,
                'method' => static::LISTENER_METHOD ?? $this->listenerMethod,
                '_with' => 'params',
            ])
            ->setLimit(1)
            ->retrieve();

        if ($listeners->isEmpty()) {
            throw new \RuntimeException('Listeners not found.');
        }
        $this->listener = $listeners->first();
    }
}