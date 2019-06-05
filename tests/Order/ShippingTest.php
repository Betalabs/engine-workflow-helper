<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Order;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order\Shipping;
use Betalabs\EngineWorkflowHelper\Tests\AbstractWorkflow;
use Illuminate\Foundation\Testing\WithFaker;

class ShippingTest extends AbstractWorkflow
{
    use WithFaker;

    public function testCreate()
    {
        $name = 'testName';
        $identification = 'testId';
        $eventName = 'shipping';
        $classPath = 'App\Listeners\EngineListeners\AppDispatcher';
        $classMethod = 'post';

        $event = $this->mockEventIndexerByName(
            $this->eventOrListenerIndexerReturn(['orderId', 'trackingCode']),
            $eventName
        );
        $listener = $this->mockListenerIndexer(
            $this->eventOrListenerIndexerReturn([
                'appRegistryId',
                'uri',
                'data',
                'uriConcat'
            ]),
            $classPath,
            $classMethod
        );

        $this->mockWorkflowCreator($name, $event, $identification, $workflow = (object)['id' => 1]);

        $stepParams = $this->mockStepParams($listener, $event);
        $step = $this->mockStep(
            $stepParams,
            $workflow->id,
            $listener->id
        );

        $this->mockWorkflowUpdater($workflow, $step);

        /**@var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order\Shipping $shipping**/
        $shipping = resolve(Shipping::class);
        $shipping->setEventName($eventName)
            ->setEngineRegistryId(1)
            ->setAppEndpoint('orders')
            ->setName($name)
            ->setIdentification($identification)
            ->create();
    }

    private function mockStepParams($listener, $event)
    {
        return [
            [
                'engine_listener_param_id' => $listener->params[0]->id,
                'value' => 1
            ],
            [
                'engine_listener_param_id' => $listener->params[1]->id,
                'value' => 'orders/'
            ],
            [
                'engine_listener_param_id' => $listener->params[2]->id,
                'engine_event_param_id' => $event->params[0]->id
            ],
            [
                'engine_listener_param_id' => $listener->params[2]->id,
                'engine_event_param_id' => $event->params[1]->id
            ],
            [
                'engine_listener_param_id' => $listener->params[3]->id,
                'engine_event_param_id' => $event->params[0]->id
            ],
            [
                'engine_listener_param_id' => $listener->params[3]->id,
                'value' => '/shipment'
            ]
        ];
    }
}