<?php

namespace Betalabs\EngineWorkflowHelper\VirtualEntity;


use Betalabs\EngineWorkflowHelper\AbstractIndexer;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Http\Response;

class Retrieval extends AbstractIndexer
{

    /**
     * @var string
     */
    protected $exceptionMessage = 'Virtual Entity could not be retrieved.';
    /**
     * @var string
     */
    private $endpoint = "";

    /**
     * Return Engine endpoint
     *
     * @return string
     */
    protected function endpoint(): string
    {
        return "virtual-entities/{$this->endpoint}";
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
     * @param string $endpoint
     * @return Retrieval
     */
    public function setEndpoint(string $endpoint): Retrieval
    {
        $this->endpoint = $endpoint;
        return $this;
    }
}
