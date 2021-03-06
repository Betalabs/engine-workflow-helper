<?php

namespace Betalabs\EngineWorkflowHelper;

use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;
use Betalabs\EngineWorkflowHelper\Traits\SetEventByName;

abstract class AbstractPriceGroup extends AbstractWorkflow
{
    use SetEventByName;

    /**
     * @var int
     */
    protected $engineRegistryId;
    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @param string $endpoint
     * @return \Betalabs\EngineWorkflowHelper\AbstractPriceGroup
     */
    public function setEndpoint(string $endpoint): AbstractPriceGroup
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @param int $engineRegistryId
     * @return \Betalabs\EngineWorkflowHelper\AbstractWorkflow
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

        $workflow = $this->createWorkflow();

        $priceGroupParam = $this->searchParam('priceGroup', $this->event->params);
        $channelIdsParam = $this->searchParam('channelIds', $this->event->params);

        $appRegistryParam = $this->searchParam('appRegistryId', $this->listener->params);
        $appUriParam = $this->searchParam('uri', $this->listener->params);
        $data = $this->searchParam('data', $this->listener->params);

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
                    'value' => $this->endpoint,
                ],
                [
                    'engine_listener_param_id' => $data->id,
                    'engine_event_param_id' => $priceGroupParam->id
                ],
                [
                    'engine_listener_param_id' => $data->id,
                    'engine_event_param_id' => $channelIdsParam->id
                ]
            ])
            ->create();

        $this->workflowUpdater
            ->setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->update();
    }
}
