<?php

namespace Betalabs\EngineWorkflowHelper\Workflow\Step;

use Betalabs\EngineWorkflowHelper\AbstractCreator;
use Betalabs\EngineWorkflowHelper\Traits\Payload;

class Creator extends AbstractCreator
{
    use Payload;

    /**
     * @var string
     */
    protected $exceptionMessage = 'Workflow Step could not be created.';
    /**
     * @var int
     */
    private $workflowId;
    /**
     * @var int
     */
    private $listenerId;
    /**
     * @var string
     */
    private $approach;
    /**
     * @var int
     */
    private $xPosition;
    /**
     * @var int
     */
    private $yPosition;
    /**
     * @var array
     */
    private $params = [];

    /**
     * Creator constructor.
     *
     * @param int $workflowId
     * @param int $listenerId
     * @param string $approach
     */
    public function __construct(
        int $workflowId,
        int $listenerId,
        string $approach
    ) {
        $this->workflowId = $workflowId;
        $this->listenerId = $listenerId;
        $this->approach = $approach;
    }

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
     * Set the listenerId property.
     *
     * @param int $listenerId
     *
     * @return Creator
     */
    public function setListenerId(int $listenerId): Creator
    {
        $this->listenerId = $listenerId;
        return $this;
    }

    /**
     * Set the approach property.
     *
     * @return Creator
     */
    public function synchronous(): Creator
    {
        $this->approach = 'synchronous';
        return $this;
    }

    /**
     * Set the approach property.
     *
     * @return Creator
     */
    public function asynchronous(): Creator
    {
        $this->approach = 'asynchronous';
        return $this;
    }

    /**
     * Set the positions property.
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
     * Set the params property.
     *
     * @param array $params
     *
     * @return Creator
     */
    public function setParams(array $params): Creator
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Engine resource endpoint
     *
     * @return string
     */
    protected function endpoint(): string
    {
        return "workflows/{$this->workflowId}/steps";
    }

    /**
     * Resource data in Engine request format
     *
     * @return array
     */
    protected function data(): array
    {
        return $this->removeEmpty([
            'engine_listener_id' => $this->listenerId,
            'approach' => $this->approach,
            'x_position' => $this->xPosition,
            'y_position' => $this->yPosition,
            'params' => $this->params,
        ]);
    }
}