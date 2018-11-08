<?php

namespace Betalabs\EngineWorkflowHelper\Listener;

use Betalabs\EngineWorkflowHelper\AbstractIndexer;
use Psr\Http\Message\ResponseInterface;

class Indexer extends AbstractIndexer
{
    /**
     * Return Engine endpoint
     *
     * @return string
     */
    protected function endpoint(): string
    {
        return 'listeners';
    }

    /**
     * Handle request response
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    protected function handleResponse(ResponseInterface $response): void
    {
        if ($response->getStatusCode() >= 400) {
            throw new \RuntimeException('Unable to retrieve listeners');
        }
    }
}