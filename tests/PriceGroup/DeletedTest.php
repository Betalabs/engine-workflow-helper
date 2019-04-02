<?php

namespace Betalabs\EngineWorkflowHelper\Tests\PriceGroup;

use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\PriceGroup\Deleted;

class DeletedTest extends AbstractPriceGroup
{
    public function testDeleted()
    {
        $identification = 'price-group-test';
        $workflow = new \stdClass();
        $workflow->id = 23;
        $endpoint = 'products/';

        $name = 'Price Group Deleted Test';

        $this->mockListenerIndexer(
            $listener = $this->listenerIndexerReturn(),
            'App\Listeners\EngineListeners\AppDispatcher',
            'put'
        );
        $this->mockEventIndexerByName(
            $event = $this->eventIndexerReturn(),
            'PriceGroup.Deleted'
        );
        $this->mockWorkflowCreator($name, $event, $identification, $workflow);

        $step = new \stdClass();
        $step->id = 12;
        $engineRegistryId = 2;

        $step = $this->mockAppDispatcherPostOrPutStep(
            $workflow,
            $listener,
            $event,
            $engineRegistryId,
            'products/'
        );

        $this->mockWorkflowUpdater($workflow, $step);
        /** @var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\PriceGroup\Deleted $deleted**/
        $deleted = resolve(Deleted::class);
        $deleted->setEngineRegistryId($engineRegistryId)
            ->setName($name)
            ->setIdentification($identification)
            ->setEndpoint($endpoint)
            ->create();
    }
}