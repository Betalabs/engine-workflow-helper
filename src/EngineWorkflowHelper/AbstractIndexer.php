<?php

namespace Betalabs\EngineWorkflowHelper;

use Betalabs\Engine\Request;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractIndexer
{
    /**
     * @var array
     */
    protected $query = [];
    /**
     * @var int
     */
    protected $limit = 10;
    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * Set the query property.
     *
     * @param array $query
     *
     * @return self
     */
    public function setQuery(array $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Set the limit property.
     *
     * @param int $limit
     *
     * @return self
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set the offset property.
     *
     * @param int $offset
     *
     * @return self
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Return Engine endpoint
     *
     * @return string
     */
    abstract protected function endpoint(): string;

    /**
     * Handle request response
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    abstract protected function handleResponse(ResponseInterface $response): void;

    /**
     * Retrieve a resource
     *
     * @return \Illuminate\Support\Collection
     */
    public function retrieve(): Collection
    {
        $query = http_build_query(array_merge($this->query, [
            '_limit' => $this->limit,
            '_offset' => $this->offset
        ]));

        $request = Request::get();
        $index = $request->send("{$this->endpoint()}?{$query}");

        $this->handleResponse($request->getResponse());

        return collect($index->data);
    }
}