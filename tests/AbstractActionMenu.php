<?php

namespace Betalabs\EngineWorkflowHelper\Tests;



use Facades\Betalabs\EngineWorkflowHelper\Workflow\Condition\Creator as ConditionCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Updater as WorkflowUpdater;

class AbstractActionMenu extends AbstractWorkflow
{

    /**
     * @param $entityId
     * @param $workflow
     * @param $entity
     */
    protected function mockConditionCreator($entityId, $workflow, $entity): void
    {
        ConditionCreator::shouldReceive('setEngineEventParamId')
            ->with($entityId->id)
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setWorkflowId')
            ->with($workflow->id)
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setValue')
            ->with($entity)
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