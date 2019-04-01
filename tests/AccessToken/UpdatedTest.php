<?php

namespace Betalabs\EngineWorkflowHelper\Tests\AccessToken;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AccessToken\Updated;
use Betalabs\EngineWorkflowHelper\Tests\AbstractWorkflow;
use Facades\Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Workflow\Indexer as WorkflowIndexer;
use Facades\Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Workflow\Transition\Associate;

class UpdatedTest extends AbstractWorkflow
{
    public function testUpdatedWorkflow()
    {
        $event = $this->mockEventIndexerByName($this->eventIndexerReturn(), 'AccessToken.Updated');
        $listener = $this->mockListenerIndexer(
            $this->listenerIndexerReturn(),
            'App\Listeners\EngineListeners\AppDispatcher',
            'put'
        );
        $workflow = $this->workflowIndexer($event);
        $engineRegistryId = 2;
        $step = $this->mockAppDispatcherPostOrPutStep(
            $workflow,
            $listener,
            $event,
            $engineRegistryId,
            'app-access-token/'
        );
        $this->mockWorkflowUpdater($workflow, $step);
        $this->mockWorkflowAssociate($workflow, $step);

        /**@var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AccessToken\Updated $updated**/
        $updated = resolve(Updated::class);
        $updated->setEngineRegistryId($engineRegistryId)
            ->create();
    }

    private function workflowIndexer($event)
    {
        $workflow = new \stdClass();
        $workflow->id = 12;

        $workflow->engine_event = new \stdClass();
        $workflow->engine_event->params = $event->params;
        $workflow->workflow_step_id = 12;

        WorkflowIndexer::shouldReceive('setLimit')
            ->with(1000)
            ->once()
            ->andReturnSelf();
        WorkflowIndexer::shouldReceive('setQuery')
            ->with(['identification' => 'App.AccessToken.Updated'])
            ->once()
            ->andReturnSelf();
        WorkflowIndexer::shouldReceive('retrieve')
            ->once()
            ->andReturn(collect([$workflow]));
        return $workflow;
    }

    private function mockWorkflowAssociate($workflow, $step)
    {
        Associate::shouldReceive('setWorkflowId')
            ->with($workflow->id)
            ->once()
            ->andReturnSelf();
        Associate::shouldReceive('setWorkflowStepId')
            ->with($step->id)
            ->once()
            ->andReturnSelf();
        Associate::shouldReceive('setNextWorkflowStepId')
            ->with($workflow->workflow_step_id)
            ->once()
            ->andReturnSelf();
        Associate::shouldReceive('create')
            ->once()
            ->andReturn(null);
    }

    /**
     * @return \stdClass
     */
    private function eventIndexerReturn(): \stdClass
    {
        $tokenAliasId = new \stdClass();
        $tokenAliasId->name = 'token';
        $tokenAliasId->id = 14;

        $appRegistryId = new \stdClass();
        $appRegistryId->name = 'appRegistryId';
        $appRegistryId->id = 12;

        $event = new \stdClass();
        $event->params = [$tokenAliasId, $appRegistryId];
        $event->id = 2;
        return $event;
    }

    /**
     * @return \stdClass
     */
    protected function listenerIndexerReturn(): \stdClass
    {
        $appRegistryId = new \stdClass();
        $appRegistryId->name = 'appRegistryId';
        $appRegistryId->id = 3;

        $uri = new \stdClass();
        $uri->name = 'uri';
        $uri->id = 4;

        $data = new \stdClass();
        $data->name = 'data';
        $data->id = 4;

        $listener = new \stdClass();
        $listener->params = [$appRegistryId, $uri, $data];
        $listener->id = 1;
        return $listener;
    }
}