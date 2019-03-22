<?php
namespace Betalabs\EngineWorkflowHelper\Tests\Product;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Product\MultipleActionMenu;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Condition\Creator as ConditionCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;
use Facades\Betalabs\EngineWorkflowHelper\Event\Indexer as EventIndexer;

class MultipleActionMenuTest extends AbstractActionMenu
{
    public function testMultipleActionMenu()
    {
        $name = 'Action Menu Test';
        $identification = 'action-menu-test';

        $entity = new \stdClass();
        $entity->name = 'entity';
        $entity->id = 52;
        $event = new \stdClass();
        $event->params = [$entity];
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

        $this->mockConditionCreator($entity, $workflow);

        $step = new \stdClass();
        $step->id = 12;
        $this->mockStepCreator($workflow, $listener, $appRegistryId, $uri, $step);

        $this->mockWorkflowUpdater($workflow, $step);

        /** @var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Product\MultipleActionMenu $multipleActionMenu**/
        $multipleActionMenu = resolve(MultipleActionMenu::class);
        $multipleActionMenu->setName($name)
            ->setEngineRegistryId(1)
            ->setIdentification($identification)
            ->create();
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
            ->with('item-price')
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
     * @param $step
     */
    protected function mockStepCreator($workflow, $listener, $appRegistryId, $uri, $step): void
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
                    'value' => 'products/action-menu',
                ]
            ])
            ->andReturnSelf();
        StepCreator::shouldReceive('create')
            ->andReturn($step);
    }

    /**
     * @param $event
     */
    protected function mockEventIndexer($event): void
    {
        EventIndexer::shouldReceive('setQuery')
            ->with([
                'class' => 'App\Services\MenuAction\Service',
                'method' => 'multipleExtra',
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