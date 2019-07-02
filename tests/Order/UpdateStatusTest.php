<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Order;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order\UpdateStatus;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;
use Betalabs\EngineWorkflowHelper\Tests\AbstractWorkflow;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\VirtualEntity\Retrieval as VirtualEntityRetrieval;
use Facades\Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Structure\Retrieval as StructureRetrieval;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;

class UpdateStatusTest extends AbstractWorkflow
{
    use WithFaker;

    public function testUpdateStatus()
    {
        $eventName = $this->faker->word;
        $name = $this->faker->word;
        $identification = $this->faker->word;
        $orderStatus = $this->faker->word;

        $workflow = (object)['id' => $this->faker->randomNumber()];

        $this->mockListenerIndexer(
            $listener = $this->listenerIndexerReturn(),
            'App\Listeners\EngineListeners\Status',
            'changeRaw'
        );
        $this->mockEventIndexerByName(
            $event = $this->eventIndexerReturn(),
            $eventName
        );
        $this->mockWorkflowCreator($name, $event, $identification, $workflow);

        $this->mockOrderStructureRetrieval();
        $this->mockVirtualEntityRetrieval();
        $this->mockVirtualEntityRecordsRetrieval($orderStatus);

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
            ->withArgs(function($arg){
                return is_array($arg);
            })
            ->once()
            ->andReturnSelf();
        StepCreator::shouldReceive('create')
            ->once()
            ->andReturn($step = (object)['id' => $this->faker->randomNumber()]);

        $this->mockWorkflowUpdater($workflow, $step);

        /**@var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order\UpdateStatus $updateStatus**/
        $updateStatus = resolve(UpdateStatus::class);
        $updateStatus->setOrderStatus($orderStatus)
            ->setEventName($eventName)
            ->setName($name)
            ->setIdentification($identification)
            ->create();
    }

    private function eventIndexerReturn()
    {
        return (object)[
            'params' => [
                (object)[
                    'id' => $this->faker->randomNumber(),
                    'name' => 'orderId'
                ]
            ],
            'id' => $this->faker->randomNumber()
        ];
    }

    private function listenerIndexerReturn()
    {
        return (object)[
            'params' => [
                (object)[
                    'id' => $this->faker->randomNumber(),
                    'name' => 'modelId'
                ],
                (object)[
                    'id' => $this->faker->randomNumber(),
                    'name' => 'modelType'
                ],
                (object)[
                    'id' => $this->faker->randomNumber(),
                    'name' => 'formId'
                ],
                (object)[
                    'id' => $this->faker->randomNumber(),
                    'name' => 'extraFieldId'
                ],
                (object)[
                    'id' => $this->faker->randomNumber(),
                    'name' => 'statusId'
                ]
            ],
            'id' => $this->faker->randomNumber(),
        ];
    }

    private function mockOrderStructureRetrieval()
    {
        $response = (object)[
            'mapping' => (object)[
                'extra_field' => (object)[
                    'order-status' => (object)[
                        "key" => "status_16_17",
                        "type" => "entity_reference",
                        "fields" => []
                    ]
                ]
            ]
        ];
        StructureRetrieval::shouldReceive('setEndpoint')
            ->once()
            ->with("orders")
            ->andReturnSelf();
        StructureRetrieval::shouldReceive('setQuery')
            ->once()
            ->with(['data' => 'mapping'])
            ->andReturnSelf();
        StructureRetrieval::shouldReceive('retrieve')
            ->once()
            ->andReturn(collect($response));
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
                (object)[
                    "id" => 5,
                    "name" => "Status de venda",
                    "slug" => "order-statuses"
                ]
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
                "id" => 1,
                "real_id" => 5288,
                "tags" => [],
                "nome_15_16" => $orderStatus,
                "parent_id" => null
            ]]));
    }
}