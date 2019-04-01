<?php

namespace Betalabs\EngineWorkflowHelper\Tests\PriceGroup;

use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\PriceGroup\Created;

class CreatedTest extends AbstractPriceGroup
{
    public function testCreated()
    {
        $identification = 'price-group-test';
        $workflow = new \stdClass();
        $workflow->id = 23;

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
            'products/'
        );

        $this->mockWorkflowUpdater($workflow, $step);
        /** @var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\PriceGroup\Created $created**/
        $created = resolve(Created::class);
        $created->setEngineRegistryId($engineRegistryId)
            ->setName($name)
            ->setIdentification($identification)
            ->create();
    }
}