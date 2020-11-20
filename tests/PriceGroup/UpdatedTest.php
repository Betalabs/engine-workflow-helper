<?php

namespace Betalabs\EngineWorkflowHelper\Tests\PriceGroup;


use Betalabs\EngineWorkflowHelper\PriceGroup\Updated;

class UpdatedTest extends AbstractPriceGroup
{
    public function testUpdated()
    {
        $identification = 'price-group-test';
        $workflow = new \stdClass();
        $workflow->id = 23;
        $endpoint = 'products/';

        $name = 'Price Group Updated Test';

        $this->mockListenerIndexer(
            $listener = $this->listenerIndexerReturn(),
            'App\Listeners\EngineListeners\AppDispatcher',
            'put'
        );
        $this->mockEventIndexerByName(
            $event = $this->eventIndexerReturn(),
            'PriceGroup.Updated'
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
        /** @var \Betalabs\EngineWorkflowHelper\PriceGroup\Updated $updated**/
        $updated = resolve(Updated::class);
        $updated->setEngineRegistryId($engineRegistryId)
            ->setName($name)
            ->setIdentification($identification)
            ->setEndpoint($endpoint)
            ->create();
    }

}
