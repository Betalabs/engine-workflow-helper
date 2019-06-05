<?php

namespace Betalabs\EngineWorkflowHelper\Tests;

use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Creator as WorkflowCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Updater as WorkflowUpdater;
use Facades\Betalabs\EngineWorkflowHelper\Event\Indexer as EventIndexer;
use Facades\Betalabs\EngineWorkflowHelper\Listener\Indexer as ListenerIndexer;
use Illuminate\Foundation\Testing\WithFaker;

class AbstractWorkflow extends TestCase
{
    use WithFaker;

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

    protected function mockAppDispatcherPostOrPutStep(
        $workflow,
        $listener,
        $event,
        $engineRegistryId,
        $endpoint,
        $firstEngineEventParam = null
    ) {
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
        $params = [
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
        ];

        if($firstEngineEventParam) {
            unset($params[2]['engine_event_param_id']);
            $params[2]['value'] = $firstEngineEventParam;
        }

        StepCreator::shouldReceive('setParams')
            ->with($params)
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

    protected function eventOrListenerIndexerReturn(array $params): \stdClass
    {
        $params = collect($params)->map(function ($param) {
            return (object)[
                'id' => $this->faker->randomNumber(),
                'name' => $param
            ];
        })->all();

        return (object)[
            'params' => $params,
            'id' => $this->faker->randomNumber()
        ];
    }

    protected function mockStep(
        $stepParams,
        $workflowId,
        $listenerId,
        $approach = WorkflowStepApproach::SYNCHRONOUS
    ) {
        StepCreator::shouldReceive('setWorkflowId')
            ->once()
            ->with($workflowId)
            ->andReturnSelf();
        StepCreator::shouldReceive('setListenerId')
            ->once()
            ->with($listenerId)
            ->andReturnSelf();
        StepCreator::shouldReceive('setApproach')
            ->once()
            ->with($approach)
            ->andReturnSelf();
        StepCreator::shouldReceive('setParams')
            ->once()
            ->with($stepParams)
            ->andReturnSelf();
        StepCreator::shouldReceive('create')
            ->once()
            ->andReturn($step = (object)['id' => $this->faker->randomNumber()]);
        return $step;
    }
}