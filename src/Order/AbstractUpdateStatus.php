<?php


namespace Betalabs\EngineWorkflowHelper\Order;


use Betalabs\EngineWorkflowHelper\AbstractWorkflow;
use Betalabs\EngineWorkflowHelper\Traits\SetEventByName;
use Betalabs\EngineWorkflowHelper\Traits\SetOrderStatusParameters;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;

abstract class AbstractUpdateStatus extends AbstractWorkflow
{
    use SetEventByName, SetOrderStatusParameters;

    const EVENT_CLASS = '';
    const EVENT_METHOD = '';
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\Status';
    const LISTENER_METHOD = '';
    const EVENT_NAME = null;
    const ID_PARAM = null;
    const ORDER_ID_PARAM = null;

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
     * Set Workflow events, listeners and order status properties
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->setOrderStatusParameters();
    }

    /**
     * Create the workflow
     *
     */
    public function create()
    {
        $this->setUp();

        $workflow = $this->createWorkflow();

        $modelIdParam = $this->searchParam(static::ID_PARAM, $this->listener->params);
        $modelTypeIdParam = $this->searchParam('modelType', $this->listener->params);
        $formIdParam = $this->searchParam('formId', $this->listener->params);
        $extraFieldIdParam = $this->searchParam('extraFieldId', $this->listener->params);
        $statusIdParam = $this->searchParam('statusId', $this->listener->params);
        $orderId = $this->searchParam(static::ORDER_ID_PARAM, $this->event->params);

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
     * @return AbstractUpdateStatus
     */
    public function setOrderStatus(string $orderStatus): AbstractUpdateStatus
    {
        $this->orderStatus = $orderStatus;
        return $this;
    }

    /**
     * @param string $eventName
     * @return AbstractUpdateStatus
     */
    public function setEventName(string $eventName): AbstractUpdateStatus
    {
        $this->eventName = $eventName;
        return $this;
    }
}
