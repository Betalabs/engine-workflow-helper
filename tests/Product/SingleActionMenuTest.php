<?php

use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Product\SingleActionMenu;
use Betalabs\EngineWorkflowHelper\Tests\AbstractActionMenu;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;
use Facades\Betalabs\EngineWorkflowHelper\Event\Indexer as EventIndexer;

class SingleActionMenuTest extends AbstractActionMenu
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
        $this->mockEventIndexer($event, 'App\Services\VirtualEntityRecord\ActionMenuSingle', 'extra');

        $appRegistryId = new \stdClass();
        $appRegistryId->name = 'appRegistryId';
        $appRegistryId->id = 3;
        $uri = new \stdClass();
        $uri->name = 'uri';
        $uri->id = 4;
        $listener = new \stdClass();
        $listener->params = [$appRegistryId, $uri];
        $listener->id = 1;
        $this->mockListenerIndexer($listener,'App\Listeners\EngineListeners\AppDispatcher', 'get');

        $workflow = new \stdClass();
        $workflow->id = 23;
        $this->mockWorkflowCreator($name, $event, $identification, $workflow);

        $this->mockConditionCreator($entityAliasId, $workflow, 'item');

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
     * @param $workflow
     * @param $listener
     * @param $appRegistryId
     * @param $uri
     * @param $paramAliasId
     * @param $step
     */
    protected function mockStepCreator($workflow, $listener, $appRegistryId, $uri, $paramAliasId, $step): void
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
     * @param $event
     */
    protected function mockEventIndexer($event, $classPath, $classMethod)
    {
        EventIndexer::shouldReceive('setQuery')
            ->with([
                'class' => $classPath,
                'method' => $classMethod,
                '_with' => 'params',
            ])
            ->andReturnSelf();
        EventIndexer::shouldReceive('setLimit')
            ->with(1)
            ->andReturnSelf();
        EventIndexer::shouldReceive('retrieve')
            ->andReturn(collect([$event]));
    }
}