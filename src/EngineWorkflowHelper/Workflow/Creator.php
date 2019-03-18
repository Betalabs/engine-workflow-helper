<?php

namespace Betalabs\EngineWorkflowHelper\Workflow;

use Betalabs\EngineWorkflowHelper\AbstractCreator;
use Betalabs\EngineWorkflowHelper\Traits\Payload;

class Creator extends AbstractCreator
{
    use Payload;

    /**
     * @var string
     */
    protected $exceptionMessage = 'Workflow could not be created.';
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $identification;
    /**
     * @var int
     */
    private $engineEventId;
    /**
     * @var int
     */
    private $workflowStepId;
    /**
     * @var int
     */
    private $xPosition;
    /**
     * @var int
     */
    private $yPosition;

    /**
     * Set the name property.
     *
     * @param string $name
     *
     * @return Creator
     */
    public function setName(string $name): Creator
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the identification property.
     *
     * @param string $identification
     *
     * @return Creator
     */
    public function setIdentification(string $identification): Creator
    {
        $this->identification = $identification;
        return $this;
    }

    /**
     * Set the engineEventId property.
     *
     * @param int $engineEventId
     *
     * @return Creator
     */
    public function setEngineEventId(int $engineEventId): Creator
    {
        $this->engineEventId = $engineEventId;
        return $this;
    }

    /**
     * Set the workflowStepId property.
     *
     * @param int $workflowStepId
     *
     * @return Creator
     */
    public function setWorkflowStepId(int $workflowStepId): Creator
    {
        $this->workflowStepId = $workflowStepId;
        return $this;
    }

    /**
     * Set the xPosition property.
     *
     * @param int $x
     * @param int $y
     *
     * @return Creator
     */
    public function setPositions(int $x, int $y): Creator
    {
        $this->xPosition = $x;
        $this->yPosition = $y;
        return $this;
    }

    /**
     * Engine resource endpoint
     *
     * @return string
     */
    protected function endpoint(): string
    {
        return 'workflows';
    }

    /**
     * Resource data in Engine request format
     *
     * @return array
     */
    protected function data(): array
    {
        return $this->removeEmpty([
            'name' => $this->name,
            'identification' => $this->identification,
            'engine_event_id' => $this->engineEventId,
            'workflow_step_id' => $this->workflowStepId,
            'x_position' => $this->xPosition,
            'y_position' => $this->yPosition,
        ]);
    }
}