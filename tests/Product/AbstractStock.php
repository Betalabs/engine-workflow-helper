<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Product;


use Betalabs\EngineWorkflowHelper\Tests\AbstractWorkflow;

abstract class AbstractStock extends AbstractWorkflow
{
    public function makeTest(
        string $listenerClass,
        string $listenerMethod,
        string $eventName,
        string $classToTest
    ) {
        $engineRegistryId = 1;
        $endpoint = "endpoint";
        $identification = "identification";
        $name = "name";

        $event = $this->mockEventIndexerByName($this->eventIndexerReturn(), $eventName);
        $listener = $this->mockListenerIndexer($this->eventListenerIndexerReturn(), $listenerClass, $listenerMethod);
        $workflow = new \stdClass();
        $workflow->id = 23;
        $this->mockWorkflowCreator($name, $event, $identification, $workflow);

        $step = $this->mockAppDispatcherPostOrPutStep(
            $workflow,
            $listener,
            $event,
            $engineRegistryId,
            $endpoint,
            'VirtualEntityRecord'
        );

        $this->mockWorkflowUpdater($workflow, $step);

        /**@var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Product\AbstractStock $resolvedClassToTest**/
        $resolvedClassToTest = resolve($classToTest);
        $resolvedClassToTest
            ->setEngineRegistryId($engineRegistryId)
            ->setEndpoint($endpoint)
            ->setIdentification($identification)
            ->setName($name)
            ->create();
    }
    protected function eventIndexerReturn()
    {
        $virtualEntityRecord = new \stdClass();
        $virtualEntityRecord->name = 'virtualEntityRecord';
        $virtualEntityRecord->id = 14;

        $event = new \stdClass();
        $event->params = [$virtualEntityRecord, $virtualEntityRecord];
        $event->id = 2;
        return $event;
    }

    private function eventListenerIndexerReturn()
    {
        $appRegistryId = new \stdClass();
        $appRegistryId->name = 'appRegistryId';
        $appRegistryId->id = 3;

        $uri = new \stdClass();
        $uri->name = 'uri';
        $uri->id = 4;

        $data = new \stdClass();
        $data->name = 'data';
        $data->id = 2;

        $listener = new \stdClass();
        $listener->params = [$appRegistryId, $uri, $data];
        $listener->id = 1;
        return $listener;
    }
}