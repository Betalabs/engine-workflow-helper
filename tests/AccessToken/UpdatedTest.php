<?php

namespace Betalabs\EngineWorkflowHelper\Tests\AccessToken;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AccessToken\Updated;
use Betalabs\EngineWorkflowHelper\Tests\TestCase;
use Facades\Betalabs\EngineWorkflowHelper\Event\Indexer as EventIndexer;
use Facades\Betalabs\EngineWorkflowHelper\Listener\Indexer as ListenerIndexer;
use Facades\Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Workflow\Indexer as WorkflowIndexer;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Updater as WorkflowUpdater;
use Facades\Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Workflow\Transition\Associate;

class UpdatedTest extends TestCase
{
    public function testUpdatedWorkflow()
    {
        $event = $this->mockEventIndexer();
        $listener = $this->mockListenerIndexer();
        $workflow = $this->workflowIndexer($event);
        $engineRegistryId = 2;
        $step = $this->mockStep($workflow, $listener, $event, $engineRegistryId);
        $this->mockWorkflowUpdater($workflow, $step);
        $this->mockWorkflowAssociate($workflow, $step);

        /**@var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AccessToken\Updated $updated**/
        $updated = resolve(Updated::class);
        $updated->setEngineRegistryId($engineRegistryId)
            ->create();

    }

    private function mockEventIndexer()
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

        EventIndexer::shouldReceive('setQuery')
            ->with([
                'name' => 'AccessToken.Updated',
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

    protected function mockListenerIndexer()
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

        ListenerIndexer::shouldReceive('setQuery')
            ->with([
                'class' => 'App\Listeners\EngineListeners\AppDispatcher',
                'method' => 'put',
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

    private function mockStep($workflow, $listener, $event, $engineRegistryId)
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
                    'value' => 'app-access-token/',
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

    private function mockWorkflowUpdater($workflow, $step)
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
}