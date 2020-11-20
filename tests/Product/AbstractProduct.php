<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Product;


use Betalabs\EngineWorkflowHelper\Tests\AbstractWorkflow;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Condition\Creator as ConditionCreator;

class AbstractProduct extends AbstractWorkflow
{
    public function makeTest(
        string $eventClass,
        string $eventMethod,
        string $listenerClass,
        string $listenerMethod,
        string $classToTest
    ) {
        $virtualEntitySlug = new \stdClass();
        $virtualEntitySlug->name = 'virtualEntitySlug';
        $virtualEntitySlug->id = 5;
        $virtualEntityRecord = new \stdClass();
        $virtualEntityRecord->name = 'virtualEntityRecord';
        $virtualEntityRecord->id = 52;
        $event = new \stdClass();
        $event->params = [$virtualEntitySlug, $virtualEntityRecord];
        $event->id = 1;

        $this->mockEventIndexer($event, $eventClass, $eventMethod);

        $appRegistryId = new \stdClass();
        $appRegistryId->name = 'appRegistryId';
        $appRegistryId->id = 3;
        $uri = new \stdClass();
        $uri->name = 'uri';
        $uri->id = 4;
        $data = new \stdClass();
        $data->name = 'data';
        $data->id = 6;
        $listener = new \stdClass();
        $listener->params = [$appRegistryId, $uri, $data];
        $listener->id = 1;
        $this->mockListenerIndexer($listener, $listenerClass, $listenerMethod);

        $testName = 'testName';
        $testIdentification = 'testIdentification';
        $workflow = new \stdClass();
        $workflow->id = 23;
        $this->mockWorkflowCreator($testName, $event, $testIdentification, $workflow);

        $this->mockConditionCreator($virtualEntitySlug, $workflow);

        $engineRegistryId = 1;
        $step = $this->mockAppDispatcherPostOrPutStep(
            $workflow,
            $listener,
            $event,
            $engineRegistryId,
            'products/',
            'VirtualEntityRecord'
        );

        $this->mockWorkflowUpdater($workflow, $step);

        /**@var \Betalabs\EngineWorkflowHelper\Product\AbstractProduct $resolvedClassToTest**/
        $resolvedClassToTest = resolve($classToTest);
        $resolvedClassToTest->setEngineRegistryId($engineRegistryId)
            ->setName($testName)
            ->setIdentification($testIdentification)
            ->create();
    }

    /**
     * @param $virtualEntitySlug
     * @param $workflow
     */
    protected function mockConditionCreator($virtualEntitySlug, $workflow): void
    {
        ConditionCreator::shouldReceive('setEngineEventParamId')
            ->with($virtualEntitySlug->id)
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setWorkflowId')
            ->with($workflow->id)
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setValue')
            ->with('item')
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setOperator')
            ->with('=')
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setApproach')
            ->with('and')
            ->andReturnSelf();
        ConditionCreator::shouldReceive('create')
            ->andReturn($this->anything());
    }
}
