<?php

namespace Betalabs\EngineWorkflowHelper\Tests;

use Facades\Betalabs\EngineWorkflowHelper\Workflow\Creator as WorkflowCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Updater as WorkflowUpdater;
use Facades\Betalabs\EngineWorkflowHelper\Event\Indexer as EventIndexer;
use Facades\Betalabs\EngineWorkflowHelper\Listener\Indexer as ListenerIndexer;

class AbstractWorkflow extends TestCase
{
    protected function mockWorkflowUpdater($workflow, $step)
    {
        WorkflowUpdater::shouldReceive('setWorkflowId')
            ->with($workflow->id)
            ->once()
            ->andReturnSelf();
        WorkflowUpdater::shouldReceive('setWorkflowStepId')
            ->with($step->id)
            ->once()
            ->andReturnSelf();
        WorkflowUpdater::shouldReceive('update')
            ->once()
            ->andReturn(null);
    }

    protected function mockEventIndexerByName($event, $eventName)
    {
        EventIndexer::shouldReceive('setQuery')
            ->with([
                'name' => $eventName,
                '_with' => 'params'
            ])
            ->once()
            ->andReturnSelf();
        EventIndexer::shouldReceive('setLimit')
            ->with(1)
            ->once()
            ->andReturnSelf();
        EventIndexer::shouldReceive('retrieve')
            ->once()
            ->andReturn(collect([$event]));
        return $event;
    }

    protected function mockEventIndexer($event, $classPath, $classMethod)
    {
        EventIndexer::shouldReceive('setQuery')
            ->with([
                'class' => $classPath,
                'method' => $classMethod,
                '_with' => 'params',
            ])
            ->once()
            ->andReturnSelf();
        EventIndexer::shouldReceive('setLimit')
            ->with(1)
            ->once()
            ->andReturnSelf();
        EventIndexer::shouldReceive('retrieve')
            ->once()
            ->andReturn(collect([$event]));
        return $event;
    }

    protected function mockListenerIndexer($listener, $classPath, $classMethod)
    {
        ListenerIndexer::shouldReceive('setQuery')
            ->with([
                'class' => $classPath,
                'method' => $classMethod,
                '_with' => 'params',
            ])
            ->once()
            ->andReturnSelf();
        ListenerIndexer::shouldReceive('setLimit')
            ->with(1)
            ->once()
            ->andReturnSelf();
        ListenerIndexer::shouldReceive('retrieve')
            ->once()
            ->andReturn(collect([$listener]));
        return $listener;
    }

    protected function mockAppDispatcherPostOrPutStep($workflow, $listener, $event, $engineRegistryId, $endpoint)
    {
        $step = new \stdClass();
        $step->id = 21;
        StepCreator::shouldReceive('setApproach')
            ->with('synchronous')
            ->once()
            ->andReturnSelf();
        StepCreator::shouldReceive('setWorkflowId')
            ->with($workflow->id)
            ->once()
            ->andReturnSelf();
        StepCreator::shouldReceive('setListenerId')
            ->with($listener->id)
            ->once()
            ->andReturnSelf();
        StepCreator::shouldReceive('setParams')
            ->with([
                [
                    'engine_listener_param_id' => $listener->params[0]->id,
                    'value' => $engineRegistryId,
                ],
                [
                    'engine_listener_param_id' => $listener->params[1]->id,
                    'value' => $endpoint,
                ],
                [
                    'engine_listener_param_id' => $listener->params[2]->id,
                    'engine_event_param_id' => $event->params[0]->id
                ],
                [
                    'engine_listener_param_id' => $listener->params[2]->id,
                    'engine_event_param_id' => $event->params[1]->id
                ]
            ])
            ->once()
            ->andReturnSelf();
        StepCreator::shouldReceive('create')
            ->once()
            ->andReturn($step);
        return $step;
    }

    /**
     * @param $name
     * @param $event
     * @param $identification
     * @param $workflow
     */
    protected function mockWorkflowCreator($name, $event, $identification, $workflow): void
    {
        WorkflowCreator::shouldReceive('setName')
            ->with($name)
            ->andReturnSelf();
        WorkflowCreator::shouldReceive('setEngineEventId')
            ->with($event->id)
            ->andReturnSelf();
        WorkflowCreator::shouldReceive('setIdentification')
            ->with($identification)
            ->andReturnSelf();
        WorkflowCreator::shouldReceive('create')
            ->andReturn($workflow);
    }
}