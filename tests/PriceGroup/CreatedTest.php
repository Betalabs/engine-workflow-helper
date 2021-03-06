<?php

namespace Betalabs\EngineWorkflowHelper\Tests\PriceGroup;

use Betalabs\EngineWorkflowHelper\PriceGroup\Created;

class CreatedTest extends AbstractPriceGroup
{
    public function testCreated()
    {
        $identification = 'price-group-test';
        $workflow = new \stdClass();
        $workflow->id = 23;
        $endpoint = 'products/';

        $name = 'Price Group Created Test';

        $this->mockListenerIndexer(
            $listener = $this->listenerIndexerReturn(),
            'App\Listeners\EngineListeners\AppDispatcher',
            'put'
        );
        $this->mockEventIndexerByName(
            $event = $this->eventIndexerReturn(),
            'PriceGroup.Created'
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
            $endpoint
        );

        $this->mockWorkflowUpdater($workflow, $step);
        /** @var \Betalabs\EngineWorkflowHelper\PriceGroup\Created $created**/
        $created = resolve(Created::class);
        $created->setEngineRegistryId($engineRegistryId)
            ->setName($name)
            ->setIdentification($identification)
            ->setEndpoint($endpoint)
            ->create();
    }
}
