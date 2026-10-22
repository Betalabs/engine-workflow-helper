<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Order;

use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;
use Betalabs\EngineWorkflowHelper\Order\UpdateMultipleStatuses;
use Betalabs\EngineWorkflowHelper\Tests\AbstractWorkflow;
use Facades\Betalabs\EngineWorkflowHelper\Structure\Retrieval as StructureRetrieval;
use Facades\Betalabs\EngineWorkflowHelper\VirtualEntity\Retrieval as VirtualEntityRetrieval;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;
use Illuminate\Foundation\Testing\WithFaker;

class UpdateMultipleStatusesTest extends AbstractWorkflow
{
    use WithFaker;

    public function testUpdateMultipleStatusesSendsExpectedStepParams()
    {
        $eventName = $this->faker->word;
        $name = $this->faker->word;
        $identification = $this->faker->word;

        $workflow = (object)['id' => 101];

        $this->mockListenerIndexer(
            $listener = $this->listenerIndexerReturn(),
            'App\Listeners\EngineListeners\Status',
            'changeMultipleRaw'
        );
        $this->mockEventIndexerByName(
            $event = $this->eventIndexerReturn(),
            $eventName
        );
        $this->mockWorkflowCreator($name, $event, $identification, $workflow);

        $this->mockOrderStructureRetrieval();
        $this->mockVirtualEntityRetrieval();
        $this->mockVirtualEntityRecordsRetrieval('Pago');

        StepCreator::shouldReceive('setWorkflowId')
            ->with($workflow->id)
            ->once()
            ->andReturnSelf();
        StepCreator::shouldReceive('setListenerId')
            ->with($listener->id)
            ->once()
            ->andReturnSelf();
        StepCreator::shouldReceive('setApproach')
            ->with(WorkflowStepApproach::SYNCHRONOUS)
            ->once()
            ->andReturnSelf();
        StepCreator::shouldReceive('setParams')
            ->with([
                ['engine_listener_param_id' => 1, 'engine_event_param_id' => 10],
                ['engine_listener_param_id' => 2, 'value' => 'App\Models\Order'],
                ['engine_listener_param_id' => 3, 'value' => '16'],
                ['engine_listener_param_id' => 4, 'value' => '17'],
                ['engine_listener_param_id' => 5, 'value' => 5288],
            ])
            ->once()
            ->andReturnSelf();
        StepCreator::shouldReceive('create')
            ->once()
            ->andReturn($step = (object)['id' => 202]);

        $this->mockWorkflowUpdater($workflow, $step);

        resolve(UpdateMultipleStatuses::class)
            ->setOrderStatus('Pago')
            ->setEventName($eventName)
            ->setName($name)
            ->setIdentification($identification)
            ->create();
    }

    public function testUnknownOrderStatusThrowsException()
    {
        $this->mockListenerIndexer(
            $this->listenerIndexerReturn(),
            'App\Listeners\EngineListeners\Status',
            'changeMultipleRaw'
        );
        $this->mockEventIndexerByName($this->eventIndexerReturn(), 'event');

        $this->mockOrderStructureRetrieval();
        $this->mockVirtualEntityRetrieval();
        $this->mockVirtualEntityRecordsRetrieval('Pago');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cancelado Virtual entity record not found.');

        resolve(UpdateMultipleStatuses::class)
            ->setOrderStatus('Cancelado')
            ->setEventName('event')
            ->setName('name')
            ->setIdentification('identification')
            ->create();
    }

    private function eventIndexerReturn()
    {
        return (object)[
            'params' => [
                (object)['id' => 10, 'name' => 'orderIds'],
            ],
            'id' => 20,
        ];
    }

    private function listenerIndexerReturn()
    {
        return (object)[
            'params' => [
                (object)['id' => 1, 'name' => 'modelIds'],
                (object)['id' => 2, 'name' => 'modelType'],
                (object)['id' => 3, 'name' => 'formId'],
                (object)['id' => 4, 'name' => 'extraFieldId'],
                (object)['id' => 5, 'name' => 'statusId'],
            ],
            'id' => 30,
        ];
    }

    private function mockOrderStructureRetrieval()
    {
        StructureRetrieval::shouldReceive('setEndpoint')
            ->once()
            ->with('orders')
            ->andReturnSelf();
        StructureRetrieval::shouldReceive('setQuery')
            ->once()
            ->with(['data' => 'mapping'])
            ->andReturnSelf();
        StructureRetrieval::shouldReceive('retrieve')
            ->once()
            ->andReturn(collect((object)[
                'mapping' => (object)[
                    'extra_field' => (object)[
                        'order-status' => (object)[
                            'key' => 'status_16_17',
                            'type' => 'entity_reference',
                            'fields' => [],
                        ],
                    ],
                ],
            ]));
    }

    private function mockVirtualEntityRetrieval()
    {
        VirtualEntityRetrieval::shouldReceive('setQuery')
            ->once()
            ->with(['slug' => 'order-statuses'])
            ->andReturnSelf();
        VirtualEntityRetrieval::shouldReceive('retrieve')
            ->once()
            ->andReturn(collect([
                (object)['id' => 5, 'name' => 'Status de venda', 'slug' => 'order-statuses'],
            ]));
    }

    private function mockVirtualEntityRecordsRetrieval($orderStatus)
    {
        VirtualEntityRetrieval::shouldReceive('setQuery')
            ->once()
            ->with([])
            ->andReturnSelf();
        VirtualEntityRetrieval::shouldReceive('setEndpoint')
            ->once()
            ->with('5/records')
            ->andReturnSelf();
        VirtualEntityRetrieval::shouldReceive('retrieve')
            ->once()
            ->andReturn(collect([(object)[
                'id' => 1,
                'real_id' => 5288,
                'tags' => [],
                'nome_15_16' => $orderStatus,
                'parent_id' => null,
            ]]));
    }
}
