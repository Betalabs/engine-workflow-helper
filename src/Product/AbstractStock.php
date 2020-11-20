<?php

namespace Betalabs\EngineWorkflowHelper\Product;

use Betalabs\EngineWorkflowHelper\AbstractWorkflow;
use Betalabs\EngineWorkflowHelper\Traits\SetEventByName;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;

abstract class AbstractStock extends AbstractWorkflow
{
    use SetEventByName;
    /**
     * @var string
     */
    protected $endpoint;
    /**
     * @var int
     */
    protected $engineRegistryId;

    /**
     * @param int $engineRegistryId
     * @return \Betalabs\EngineWorkflowHelper\Product\AbstractStock
     */
    public function setEngineRegistryId(int $engineRegistryId): AbstractStock
    {
        $this->engineRegistryId = $engineRegistryId;
        return $this;
    }

    /**
     * @param string $endpoint
     * @return \Betalabs\EngineWorkflowHelper\Product\AbstractStock
     */
    public function setEndpoint(string $endpoint): AbstractStock
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Create Product Stock Workflow
     *
     * @return mixed|void
     */
    public function create()
    {
        $this->setUp();
        $workflow = $this->createWorkflow();
        $appRegistryParam = $this->searchParam('appRegistryId', $this->listener->params);
        $appUriParam = $this->searchParam('uri', $this->listener->params);
        $data = $this->searchParam('data', $this->listener->params);
        $virtualEntityRecordParam = $this->searchParam('virtualEntityRecord', $this->event->params);

        $step = $this->stepCreator
            ->setWorkflowId($workflow->id)
            ->setListenerId($this->listener->id)
            ->setApproach(WorkflowStepApproach::SYNCHRONOUS)
            ->setParams([
                [
                    'engine_listener_param_id' => $appRegistryParam->id,
                    'value' => $this->engineRegistryId,
                ],
                [
                    'engine_listener_param_id' => $appUriParam->id,
                    'value' => $this->endpoint ?? 'products/',
                ],
                [
                    'engine_listener_param_id' => $data->id,
                    'value' => 'VirtualEntityRecord'
                ],
                [
                    'engine_listener_param_id' => $data->id,
                    'engine_event_param_id' => $virtualEntityRecordParam->id
                ]
            ])
            ->create();

        $this->workflowUpdater
            ->setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->update();
    }
}
