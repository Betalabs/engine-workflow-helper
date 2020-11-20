<?php

namespace Betalabs\EngineWorkflowHelper\Structure;


use Betalabs\EngineWorkflowHelper\AbstractIndexer;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Http\Response;

class Retrieval extends AbstractIndexer
{
    /**
     * @var string
     */
    protected $exceptionMessage = 'Structure could not be retrieved.';
    /**
     * @var
     */
    private $endpoint;

    /**
     * Return Engine endpoint
     *
     * @return string
     */
    protected function endpoint(): string
    {
        return $this->endpoint . '/structure';
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

    /**
     * @param mixed $endpoint
     * @return Retrieval
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }
}
