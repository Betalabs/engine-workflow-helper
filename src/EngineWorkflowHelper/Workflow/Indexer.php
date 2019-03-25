<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Workflow;


use Betalabs\EngineWorkflowHelper\AbstractIndexer;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Http\Response;

class Indexer extends AbstractIndexer
{
    /**
     * @var string
     */
    protected $exceptionMessage = 'Workflows could not be retrieved.';
    /**
     * Return Engine endpoint
     *
     * @return string
     */
    protected function endpoint(): string
    {
        return 'workflows';
    }

    /**
     * Handle request response
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    protected function handleResponse(ResponseInterface $response): void
    {
        if ($response->getStatusCode() != Response::HTTP_OK) {
            throw new \RuntimeException($this->exceptionMessage);
        }
    }
}