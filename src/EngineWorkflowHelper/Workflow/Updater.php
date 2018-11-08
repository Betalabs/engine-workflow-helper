<?php

namespace Betalabs\EngineWorkflowHelper\Workflow;

use Betalabs\EngineWorkflowHelper\AbstractUpdater;
use Betalabs\EngineWorkflowHelper\Traits\Payload;

class Updater extends AbstractUpdater
{
    use Payload;

    /**
     * @var string
     */
    protected $exceptionMessage = 'Workflow could not be updated.';
    /**
     * @var int
     */
    private $workflowId;
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
     * Set the workflowId property.
     *
     * @param int $workflowId
     *
     * @return Updater
     */
    public function setWorkflowId(int $workflowId): Updater
    {
        $this->workflowId = $workflowId;
        return $this;
    }

    /**
     * Set the name property.
     *
     * @param string $name
     *
     * @return Updater
     */
    public function setName(string $name): Updater
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the identification property.
     *
     * @param string $identification
     *
     * @return Updater
     */
    public function setIdentification(string $identification): Updater
    {
        $this->identification = $identification;
        return $this;
    }

    /**
     * Set the engineEventId property.
     *
     * @param int $engineEventId
     *
     * @return Updater
     */
    public function setEngineEventId(int $engineEventId): Updater
    {
        $this->engineEventId = $engineEventId;
        return $this;
    }

    /**
     * Set the workflowStepId property.
     *
     * @param int $workflowStepId
     *
     * @return Updater
     */
    public function setWorkflowStepId(int $workflowStepId): Updater
    {
        $this->workflowStepId = $workflowStepId;
        return $this;
    }

    /**
     * Set the positions property.
     *
     * @param int $x
     * @param int $y
     *
     * @return Updater
     */
    public function setPositions(int $x, int $y): Updater
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
        return "workflows/{$this->workflowId}";
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