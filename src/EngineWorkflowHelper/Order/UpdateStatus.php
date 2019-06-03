<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractWorkflow;
use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Traits\SetEventByName;
use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Traits\SetOrderStatusParameters;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;

class UpdateStatus extends AbstractWorkflow
{
    use SetEventByName, SetOrderStatusParameters;

    const EVENT_CLASS = '';
    const EVENT_METHOD = '';
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\Status';
    const LISTENER_METHOD = 'changeRaw';
    const EVENT_NAME = null;

    protected function setUp()
    {
        parent::setUp();
        $this->setOrderStatusParameters();
    }
    /**
     * @var int
     */
    private $virtualEntityRecordStatusId;

    /**
     * @var int
     */
    private $orderStatusFormId;

    /**
     * @var int
     */
    private $orderStatusExtraFieldId;

    /**
     * @var string
     */
    private $orderStatus;
    /**
     * @var string
     */
    private $eventName;

    /**
     * Create the workflow
     *
     */
    public function create()
    {
        $this->setUp();

        $workflow = $this->createWorkflow();

        $modelIdParam = $this->searchParam('modelId', $this->listener->params);
        $modelTypeIdParam = $this->searchParam('modelType', $this->listener->params);
        $formIdParam = $this->searchParam('formId', $this->listener->params);
        $extraFieldIdParam = $this->searchParam('extraFieldId', $this->listener->params);
        $statusIdParam = $this->searchParam('statusId', $this->listener->params);
        $orderId = $this->searchParam('orderId', $this->event->params);

        $step = $this->stepCreator
            ->setWorkflowId($workflow->id)
            ->setListenerId($this->listener->id)
            ->setApproach(WorkflowStepApproach::SYNCHRONOUS)
            ->setParams([
                [
                    'engine_listener_param_id' => $modelIdParam->id,
                    'engine_event_param_id' => $orderId->id,
                ],
                [
                    'engine_listener_param_id' => $modelTypeIdParam->id,
                    'value' => 'App\Models\Order',
                ],
                [
                    'engine_listener_param_id' => $formIdParam->id,
                    'value' => $this->orderStatusFormId,
                ],
                [
                    'engine_listener_param_id' => $extraFieldIdParam->id,
                    'value' => $this->orderStatusExtraFieldId,
                ],
                [
                    'engine_listener_param_id' => $statusIdParam->id,
                    'value' => $this->virtualEntityRecordStatusId,
                ],
            ])
            ->create();

        $this->workflowUpdater
            ->setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->update();
    }

    /**
     * @param string $orderStatus
     * @return UpdateStatus
     */
    public function setOrderStatus(string $orderStatus): UpdateStatus
    {
        $this->orderStatus = $orderStatus;
        return $this;
    }

    /**
     * @param string $eventName
     * @return UpdateStatus
     */
    public function setEventName(string $eventName): UpdateStatus
    {
        $this->eventName = $eventName;
        return $this;
    }
}