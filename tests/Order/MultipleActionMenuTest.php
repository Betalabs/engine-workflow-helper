<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Order;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order\MultipleActionMenu;
use Betalabs\EngineWorkflowHelper\Tests\AbstractActionMenu;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;

class MultipleActionMenuTest extends AbstractActionMenu
{
    public function testMultipleActionMenu()
    {
        $endpoint = 'orders/action-menu';
        $engineRegistryId = 1;
        $name = 'name';
        $identification = 'identification';

        $this->mockEventIndexer(
            $event = (object)[
                'params' => [
                    $entity = (object)[
                        'id' => 1,
                        'name' => 'entity'
                    ]
                ],
                'id' => 123,
            ],
            'App\Services\MenuAction\Service',
             'multipleExtra'
        );

        $this->mockListenerIndexer(
            $listener = (object)[
                'params' => [
                    $appRegistryId = (object)[
                        'name' => 'appRegistryId',
                        'id' => 12
                    ],
                    $uri =(object)[
                        'name' => 'uri',
                        'id' => 13
                    ],
                ],
                'id' => 321
            ],
            'App\Listeners\EngineListeners\AppDispatcher',
            'get'
        );

        $this->mockWorkflowCreator($name, $event, $identification, $workflow = (object)['id' => 1]);
        $this->mockConditionCreator($entity, $workflow, 'order');
        $this->mockStepCreator($workflow, $listener, $appRegistryId, $uri, $step = (object)['id' => 1]);
        $this->mockWorkflowUpdater($workflow, $step);

        /**@var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order\MultipleActionMenu $multipleActionMenu **/
        $multipleActionMenu  = resolve(MultipleActionMenu::class);
        $multipleActionMenu
            ->setEndpoint($endpoint)
            ->setEngineRegistryId($engineRegistryId)
            ->setName($name)
            ->setIdentification($identification)
            ->create();
    }

    private function mockStepCreator($workflow, $listener, $appRegistryId, $uri, $step)
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
                    'value' => 'orders/action-menu',
                ]
            ])
            ->andReturnSelf();
        StepCreator::shouldReceive('create')
            ->andReturn($step);
    }
}