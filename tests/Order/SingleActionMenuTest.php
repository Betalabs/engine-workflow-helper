<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Order;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order\SingleActionMenu;
use Betalabs\EngineWorkflowHelper\Tests\AbstractActionMenu;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;

class SingleActionMenuTest extends AbstractActionMenu
{
    public function testSingleActionMenu()
    {
        $engineRegistryId = 1;
        $endpointSuffix = '/action-menu';
        $endpoint = 'orders/';
        $identification = 'identification';
        $name = 'name';

        $this->mockEventIndexer(
            $event = (object)[
                'params' => [
                    $entity = (object)[
                        'id' => 1,
                        'name' => 'entity'
                    ],
                    $eventIdParam = (object)[
                        'id' => 12,
                        'name' => 'id'
                    ]
                ],
                'id' => 123,
            ],
            'App\Services\MenuAction\Service',
            'singleExtra'
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
                    $id =(object)[
                        'name' => 'id',
                        'id' => 14
                    ],
                ],
                'id' => 321
            ],
            'App\Listeners\EngineListeners\AppDispatcher',
            'get'
        );

        $this->mockWorkflowCreator($name, $event, $identification, $workflow = (object)['id' => 1]);
        $this->mockConditionCreator($entity, $workflow, 'order');
        $this->mockStepCreator($workflow, $listener, $appRegistryId, $uri, $eventIdParam, $step = (object)['id' => 1]);
        $this->mockWorkflowUpdater($workflow, $step);

        /**@var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order\SingleActionMenu $singleActionMenu **/
        $singleActionMenu = resolve(SingleActionMenu::class);
        $singleActionMenu
            ->setEngineRegistryId($engineRegistryId)
            ->setEndpointSuffix($endpointSuffix)
            ->setEndpoint($endpoint)
            ->setIdentification($identification)
            ->setName($name)
            ->create();
    }

    private function mockStepCreator($workflow, $listener, $appRegistryId, $uri, $id, $step)
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
                    'value' => 'orders/',
                ],
                [
                    'engine_event_param_id' => $id->id,
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
}