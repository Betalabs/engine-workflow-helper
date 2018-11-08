<?php

namespace Betalabs\EngineWorkflowHelper\Event;

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
        return 'events';
    }

    /**
     * Handle request response
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    protected function handleResponse(ResponseInterface $response): void
    {
        if ($response->getStatusCode() >= 300) {
            throw new \RuntimeException(
                'Engine Events could not be retrieved.'
            );
        }
    }
}