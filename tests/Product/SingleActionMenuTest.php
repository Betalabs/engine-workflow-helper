<?php


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Product\SingleActionMenu;
use Betalabs\EngineWorkflowHelper\Tests\TestCase;
use Facades\Betalabs\EngineWorkflowHelper\Event\Indexer as EventIndexer;
use Facades\Betalabs\EngineWorkflowHelper\Listener\Indexer as ListenerIndexer;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Updater as WorkflowUpdater;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Creator as WorkflowCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Condition\Creator as ConditionCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;

class SingleActionMenuTest extends TestCase
{
    public function testSingleActionMenu()
    {
        $name = 'Action Menu Test';
        $identification = 'action-menu-test';

        $paramAliasId = new \stdClass();
        $paramAliasId->name = 'aliasId';
        $paramAliasId->id = 5;
        $entityAliasId = new \stdClass();
        $entityAliasId->name = 'entity';
        $entityAliasId->id = 52;
        $event = new \stdClass();
        $event->params = [$paramAliasId, $entityAliasId];
        $event->id = 1;
        $this->mockEventIndexer($event);

        $appRegistryId = new \stdClass();
        $appRegistryId->name = 'appRegistryId';
        $appRegistryId->id = 3;
        $uri = new \stdClass();
        $uri->name = 'uri';
        $uri->id = 4;
        $listener = new \stdClass();
        $listener->params = [$appRegistryId, $uri];
        $listener->id = 1;
        $this->mockListenerIndexer($listener);

        $workflow = new \stdClass();
        $workflow->id = 23;
        $this->mockWorkflowCreator($name, $event, $identification, $workflow);

        $this->mockConditionCreator($entityAliasId, $workflow);

        $step = new \stdClass();
        $step->id = 12;
        $this->mockStepCreator($workflow, $listener, $appRegistryId, $uri, $paramAliasId, $step);

        $this->mockWorkflowUpdater($workflow, $step);

        /** @var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Product\SingleActionMenu $singleActionMenu**/
        $singleActionMenu = resolve(SingleActionMenu::class);
        $singleActionMenu->setName($name)
            ->setEngineRegistryId(1)
            ->setIdentification($identification)
            ->create();
    }

    /**
     * @param $event
     */
    private function mockEventIndexer($event): void
    {
        EventIndexer::shouldReceive('setQuery')
            ->with([
                'class' => 'App\Services\VirtualEntityRecord\ActionMenuSingle',
                'method' => 'extra',
                '_with' => 'params',
            ])
            ->andReturnSelf();
        EventIndexer::shouldReceive('setLimit')
            ->with(1)
            ->andReturnSelf();
        EventIndexer::shouldReceive('retrieve')
            ->andReturn(collect([$event]));
    }

    /**
     * @param $listener
     */
    private function mockListenerIndexer($listener): void
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

    /**
     * @param $name
     * @param $event
     * @param $identification
     * @param $workflow
     */
    private function mockWorkflowCreator($name, $event, $identification, $workflow): void
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
    private function mockConditionCreator($entityAliasId, $workflow): void
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
     * @param $listener
     * @param $appRegistryId
     * @param $uri
     * @param $paramAliasId
     * @param $step
     */
    private function mockStepCreator($workflow, $listener, $appRegistryId, $uri, $paramAliasId, $step): void
    {
        StepCreator::shouldReceive('setApproach')
            ->with('synchronous')
            ->andReturnSelf();
        StepCreator::shouldReceive('setWorkflowId')
            ->with($workflow->id)
            ->andReturnSelf();
        StepCreator::shouldReceive('setListenerId')
            ->with($listener->id)
            ->andReturnSelf();
        StepCreator::shouldReceive('setParams')
            ->with([
                [
                    'engine_listener_param_id' => $appRegistryId->id,
                    'value' => 1,
                ],
                [
                    'engine_listener_param_id' => $uri->id,
                    'value' => 'products/',
                ],
                [
                    'engine_event_param_id' => $paramAliasId->id,
                    'engine_listener_param_id' => $uri->id,
                ],
                [
                    'engine_listener_param_id' => $uri->id,
                    'value' => '/action-menu',
                ],
            ])
            ->andReturnSelf();
        StepCreator::shouldReceive('create')
            ->andReturn($step);
    }

    /**
     * @param $workflow
     * @param $step
     */
    private function mockWorkflowUpdater($workflow, $step): void
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