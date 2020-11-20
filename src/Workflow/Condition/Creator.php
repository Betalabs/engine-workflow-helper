<?php

namespace Betalabs\EngineWorkflowHelper\Workflow\Condition;

use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionApproach;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionOperator;
use Betalabs\EngineWorkflowHelper\AbstractCreator;

class Creator extends AbstractCreator
{
    /**
     * @var string
     */
    protected $exceptionMessage = 'Workflow Condition could not be created.';
    /**
     * @var int
     */
    private $workflowId;
    /**
     * @var int
     */
    private $engineEventParamId;
    /**
     * @var string
     */
    private $value;
    /**
     * @var WorkflowConditionOperator
     */
    private $operator;
    /**
     * @var WorkflowConditionApproach
     */
    private $approach;

    /**
     * Set the workflowId property.
     *
     * @param int $workflowId
     *
     * @return Creator
     */
    public function setWorkflowId(int $workflowId): Creator
    {
        $this->workflowId = $workflowId;
        return $this;
    }

    /**
     * Set the engineEventParamId property.
     *
     * @param int $engineEventParamId
     *
     * @return Creator
     */
    public function setEngineEventParamId(int $engineEventParamId): Creator
    {
        $this->engineEventParamId = $engineEventParamId;
        return $this;
    }

    /**
     * Set the value property.
     *
     * @param string $value
     *
     * @return Creator
     */
    public function setValue(string $value): Creator
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Set the operator property.
     *
     * @param string $operator
     *
     * @return Creator
     */
    public function setOperator(string $operator): Creator
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Set the approach property.
     *
     * @param string $approach
     *
     * @return Creator
     */
    public function setApproach(string $approach): Creator
    {
        $this->approach = $approach;
        return $this;
    }

    /**
     * Engine resource endpoint
     *
     * @return string
     */
    protected function endpoint(): string
    {
        return "workflows/{$this->workflowId}/conditions";
    }

    /**
     * Resource data in Engine request format
     *
     * @return array
     */
    protected function data(): array
    {
        return [
            'engine_event_param_id' => $this->engineEventParamId,
            'value' => $this->value,
            'operator' => $this->operator,
            'approach' => $this->approach,
        ];
    }
}