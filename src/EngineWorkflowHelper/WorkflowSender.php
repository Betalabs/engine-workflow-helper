<?php

namespace Betalabs\EngineWorkflowHelper;

use Betalabs\EngineWorkflowHelper\Event\Indexer as EventIndexer;
use Betalabs\EngineWorkflowHelper\Listener\Indexer as ListenerIndexer;
use Betalabs\EngineWorkflowHelper\Workflow\Updater as WorkflowUpdater;

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
     */
    public function __construct(
        EventIndexer $eventIndexer,
        ListenerIndexer $listenerIndexer,
        WorkflowUpdater $workflowUpdater
    )
    {
        $this->eventIndexer = $eventIndexer;
        $this->listenerIndexer = $listenerIndexer;
        $this->workflowUpdater = $workflowUpdater;
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
    protected function searchEventParam(string $name, array $params)
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
                'class' => static::EVENT_CLASS,
                'method' => static::EVENT_METHOD,
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
                'class' => static::LISTENER_CLASS,
                'method' => static::LISTENER_METHOD,
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