<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Product;


use Betalabs\EngineWorkflowHelper\Tests\TestCase;

use Facades\Betalabs\EngineWorkflowHelper\Workflow\Condition\Creator as ConditionCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Creator as WorkflowCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Updater as WorkflowUpdater;
use Facades\Betalabs\EngineWorkflowHelper\Listener\Indexer as ListenerIndexer;

class AbstractActionMenu extends TestCase
{

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

    /**
     * @param $entityAliasId
     * @param $workflow
     */
    protected function mockConditionCreator($entityAliasId, $workflow): void
    {
        ConditionCreator::shouldReceive('setEngineEventParamId')
            ->with($entityAliasId->id)
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setWorkflowId')
            ->with($workflow->id)
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setValue')
            ->with('items')
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setOperator')
            ->with('=')
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setApproach')
            ->with('and')
            ->andReturnSelf();
        ConditionCreator::shouldReceive('create')
            ->andReturn($this->anything());
    }



    /**
     * @param $workflow
     * @param $step
     */
    protected function mockWorkflowUpdater($workflow, $step): void
    {
        WorkflowUpdater::shouldReceive('setWorkflowId')
            ->with($workflow->id)
            ->andReturnSelf();
        WorkflowUpdater::shouldReceive('setWorkflowStepId')
            ->with($step->id)
            ->andReturnSelf();
        WorkflowUpdater::shouldReceive('update')
            ->andReturn($this->anything());
    }

    /**
     * @param $listener
     */
    protected function mockListenerIndexer($listener): void
    {
        ListenerIndexer::shouldReceive('setQuery')
            ->with([
                'class' => 'App\Listeners\EngineListeners\AppDispatcher',
                'method' => 'get',
                '_with' => 'params',
            ])
            ->andReturnSelf();
        ListenerIndexer::shouldReceive('setLimit')
            ->with(1)
            ->andReturnSelf();
        ListenerIndexer::shouldReceive('retrieve')
            ->andReturn(collect([$listener]));
    }
}