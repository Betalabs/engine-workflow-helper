<?php

namespace Betalabs\EngineWorkflowHelper;

use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Http\Response;
use Betalabs\Engine\Request;

abstract class AbstractCreator
{
    /**
     * @var string
     */
    protected $exceptionMessage = 'Resource data could not be created.';

    /**
     * Engine resource endpoint
     *
     * @return string
     */
    abstract protected function endpoint(): string;

    /**
     * Resource data in Engine request format
     *
     * @return array
     */
    abstract protected function data(): array;

    /**
     * Create a new resource
     *
     * @return mixed
     */
    public function create()
    {
        try {
            $post = Request::post();
            $response = $post->send($this->endpoint(), $this->data());
        } catch (BadResponseException $e) {
            \Log::error($e->getMessage(), $e->getTrace());
            $post = $e;
        }

        $this->handleResponse($post->getResponse());

        return $response->data;
    }

    /**
     * Handle request response
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    protected function handleResponse(ResponseInterface $response): void
    {
        if ($response->getStatusCode() != Response::HTTP_CREATED) {
            \Log::error(
                $response->getBody()->getContents(), 
                [
                    'status_code' => $response->getStatusCode(),
                    'endpoint' => $this->endpoint(),
                    'data' => $this->data(),
                ]
            );
            throw new \RuntimeException($this->exceptionMessage);
        }
    }
}
