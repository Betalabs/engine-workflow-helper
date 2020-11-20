<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order;

use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AppDispatcher\Post;
use Illuminate\Support\Collection;

class Shipping extends Post
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected function listenerParams(): Collection
    {
        return parent::listenerParams()->merge(collect([
            'uriConcat',
        ]));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function eventParams(): Collection
    {
        return collect([
            'orderId',
            'trackingCode',
        ]);
    }

    /**
     * @return array
     */
    protected function createStepParams(): array
    {
        return [
            [
                'engine_listener_param_id' => $this->listenerParams->get('appRegistryId')->id,
                'value' => $this->engineRegistryId
            ],
            [
                'engine_listener_param_id' => $this->listenerParams->get('uri')->id,
                'value' => $this->appEndpoint . '/'
            ],
            [
                'engine_listener_param_id' => $this->listenerParams->get('data')->id,
                'engine_event_param_id' => $this->eventParams->get('orderId')->id
            ],
            [
                'engine_listener_param_id' => $this->listenerParams->get('data')->id,
                'engine_event_param_id' => $this->eventParams->get('trackingCode')->id
            ],
            [
                'engine_listener_param_id' => $this->listenerParams->get('uriConcat')->id,
                'engine_event_param_id' => $this->eventParams->get('orderId')->id
            ],
            [
                'engine_listener_param_id' => $this->listenerParams->get('uriConcat')->id,
                'value' => '/shipment'
            ]
        ];
    }
}