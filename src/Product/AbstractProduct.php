<?php

namespace Betalabs\EngineWorkflowHelper\Product;


use Betalabs\EngineWorkflowHelper\AbstractWorkflow;
use Betalabs\EngineWorkflowHelper\Enums\VirtualEntity;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionApproach as Approach;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionOperator as Operator;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;

abstract class AbstractProduct extends AbstractWorkflow
{
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
     * @return \Betalabs\EngineWorkflowHelper\Product\AbstractProduct
     */
    public function setEngineRegistryId(int $engineRegistryId): AbstractProduct
    {
        $this->engineRegistryId = $engineRegistryId;
        return $this;
    }

    /**
     * @param string $endpoint
     * @return \Betalabs\EngineWorkflowHelper\Product\AbstractProduct
     */
    public function setEndpoint(string $endpoint): AbstractProduct
    {
        $this->endpoint = $endpoint;
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
        $virtualEntitySlugParam = $this->searchParam('virtualEntitySlug', $this->event->params);
        $this->conditionCreator
            ->setWorkflowId($workflow->id)
            ->setEngineEventParamId($virtualEntitySlugParam->id)
            ->setValue(VirtualEntity::ITEM_SLUG)
            ->setOperator(new Operator(Operator::EQUAL))
            ->setApproach(new Approach(Approach:: AND))
            ->create();

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
            ])->create();

        $this->workflowUpdater
            ->setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->update();
    }
}
