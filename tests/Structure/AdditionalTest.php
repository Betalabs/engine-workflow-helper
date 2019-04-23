<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Structure;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Structure\Additional;
use Betalabs\EngineWorkflowHelper\Tests\AbstractWorkflow;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Condition\Creator as ConditionCreator;
use Facades\Betalabs\EngineWorkflowHelper\Workflow\Step\Creator as StepCreator;

class AdditionalTest extends AbstractWorkflow
{
    const EVENT_CLASS = 'App\Structures\Form\Additional';
    const EVENT_METHOD = 'fetch';
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'get';

    public function testAdditional()
    {
        $event = $this->mockEventIndexer(
            $this->eventIndexerReturn(),
            self::EVENT_CLASS,
            self::EVENT_METHOD
        );
        $listener = $this->mockListenerIndexer(
            $this->eventListenerIndexerReturn(),
            self::LISTENER_CLASS,
            self::LISTENER_METHOD
        );

        $workflow = new \stdClass();
        $workflow->id = 23;
        $this->mockWorkflowCreator($name = 'additional', $event, $identification = 'additional', $workflow);

        $this->mockConditionCreator($event, $workflow, $entity = 'entity');

        $step = new \stdClass();
        $step->id = 23;
        $structureUri = 'structureUri';
        $engineRegistryId = 1;
        $this->mockStepCreator($workflow, $listener, $step, $structureUri, $engineRegistryId);

        $this->mockWorkflowUpdater($workflow, $step);


        /**@var \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Structure\Additional $additional**/
        $additional = resolve(Additional::class);
        $additional->setEngineRegistryId($engineRegistryId)
            ->setEntity($entity)
            ->setStructureUri($structureUri)
            ->setIdentification($identification)
            ->setName($name)
            ->create();

    }

    private function eventIndexerReturn()
    {
        $entity = new \stdClass();
        $entity->name = 'entity';
        $entity->id = 14;

        $event = new \stdClass();
        $event->params = [$entity];
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

        $listener = new \stdClass();
        $listener->params = [$appRegistryId, $uri];
        $listener->id = 1;
        return $listener;
    }

    protected function mockConditionCreator($event, $workflow, $value): void
    {
        ConditionCreator::shouldReceive('setEngineEventParamId')
            ->with($event->params[0]->id)
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setWorkflowId')
            ->with($workflow->id)
            ->andReturnSelf();
        ConditionCreator::shouldReceive('setValue')
            ->with($value)
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

    private function mockStepCreator($workflow, $listener, $step, $uri, $appRegistryId)
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
                    'engine_listener_param_id' => $listener->params[0]->id,
                    'value' => $appRegistryId,
                ],
                [
                    'engine_listener_param_id' => $listener->params[1]->id,
                    'value' => $uri,
                ],
            ])
            ->andReturnSelf();
        StepCreator::shouldReceive('create')
            ->andReturn($step);
    }
}