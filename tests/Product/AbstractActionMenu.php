<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Product;


use Betalabs\EngineWorkflowHelper\Tests\AbstractWorkflow;

use Facades\Betalabs\EngineWorkflowHelper\Workflow\Condition\Creator as ConditionCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Updater as WorkflowUpdater;
use Facades\Betalabs\EngineWorkflowHelper\Listener\Indexer as ListenerIndexer;

class AbstractActionMenu extends AbstractWorkflow
{

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
}