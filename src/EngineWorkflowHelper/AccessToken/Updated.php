<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AccessToken;

use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractWorkflow;
use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Traits\SetEventByName;
use Facades\Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Workflow\Transition\Associate;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;
use Facades\Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Workflow\Indexer;

class Updated extends AbstractWorkflow
{
    use SetEventByName;

    const EVENT_CLASS = '';
    const EVENT_METHOD = '';
    const EVENT_NAME = 'AccessToken.Updated';
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'put';
    /**
     * @var int
     */
    protected $engineRegistryId;

    /**
     * @param int $engineRegistryId
     * @return \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractWorkflow
     */
    public function setEngineRegistryId(int $engineRegistryId): AbstractWorkflow
    {
        $this->engineRegistryId = $engineRegistryId;
        return $this;
    }

    /**
     * Create the workflow
     *
     */
    public function create()
    {
        $this->setUp();
        $workflow = $this->getWorkflow('App.AccessToken.Updated');

        $tokenEventParam = $this->searchEventParam('token', $this->event->params);
        $appRegistryIdEventParam = $this->searchEventParam('appRegistryId', $this->event->params);

        $appRegistryParam = $this->searchEventParam('appRegistryId', $this->listener->params);
        $appUriParam = $this->searchEventParam('uri', $this->listener->params);
        $data = $this->searchEventParam('data', $this->listener->params);

        $step = $this->stepCreator
            ->setApproach(WorkflowStepApproach::SYNCHRONOUS)
            ->setWorkflowId($workflow->id)
            ->setListenerId($this->listener->id)
            ->setParams([
                [
                    'engine_listener_param_id' => $appRegistryParam->id,
                    'value' => $this->engineRegistryId,
                ],
                [
                    'engine_listener_param_id' => $appUriParam->id,
                    'value' => 'app-access-token/',
                ],
                [
                    'engine_listener_param_id' => $data->id,
                    'engine_event_param_id' => $tokenEventParam->id
                ],
                [
                    'engine_listener_param_id' => $data->id,
                    'engine_event_param_id' => $appRegistryIdEventParam->id
                ]
            ])
            ->create();

        $this->workflowUpdater
            ->setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->update();

        Associate::setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->setNextWorkflowStepId($workflow->workflow_step_id)
            ->create();
    }

    private function getWorkflow(string $identification)
    {
        /**@var \Illuminate\Support\Collection $workflow **/
        $workflow = Indexer::setLimit(1)
            ->setQuery(['identification' => $identification])
            ->retrieve();
        if ($workflow->isEmpty()) {
            throw new \RuntimeException('Update App Access Token Workflow not found');
        }
        return $workflow->last();
    }
}