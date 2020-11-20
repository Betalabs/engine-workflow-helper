<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Workflow\Transition;


use Betalabs\EngineWorkflowHelper\Traits\Payload;
use Betalabs\Engine\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Exception\RequestException;

class Associate
{
    use Payload;

    /**
     * @var int
     */
    private $workflowId;
    /**
     * @var int
     */
    private $workflowStepId;
    /**
     * @var int
     */
    private $nextWorkflowStepId;
    /**
     * @var array
     */
    private $conditions = [];

    /**
     * Set the workflowId property.
     *
     * @param int $workflowId
     *
     * @return Associate
     */
    public function setWorkflowId(int $workflowId): Associate
    {
        $this->workflowId = $workflowId;
        return $this;
    }

    /**
     * Set the workflowStepId property.
     *
     * @param int $workflowStepId
     *
     * @return Associate
     */
    public function setWorkflowStepId(int $workflowStepId): Associate
    {
        $this->workflowStepId = $workflowStepId;
        return $this;
    }

    /**
     * Set the nextWorkflowStepId property.
     *
     * @param int $nextWorkflowStepId
     *
     * @return Associate
     */
    public function setNextWorkflowStepId(int $nextWorkflowStepId): Associate
    {
        $this->nextWorkflowStepId = $nextWorkflowStepId;
        return $this;
    }

    /**
     * Set the conditions property.
     *
     * @param array $conditions
     *
     * @return Associate
     */
    public function setConditions(array $conditions): Associate
    {
        $this->conditions = $conditions;
        return $this;
    }

    /**
     * Assoaciate a new Workflow Transition
     *
     * @return mixed
     */
    public function create()
    {
        try {
            $request = Request::post();
            $request->send(
                "workflows/{$this->workflowId}/transitions",
                $this->removeEmpty([
                    'workflow_step_id' => $this->workflowStepId,
                    'next_workflow_step_id' => $this->nextWorkflowStepId,
                    'conditions' => $this->conditions,
                ])
            );

            return $request->statusCode() == Response::HTTP_NO_CONTENT;
        } catch (RequestException $e) {
            return false;
        }
    }
}