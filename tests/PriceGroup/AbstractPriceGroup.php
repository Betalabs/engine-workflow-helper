<?php

namespace Betalabs\EngineWorkflowHelper\Tests\PriceGroup;

use Betalabs\EngineWorkflowHelper\Tests\AbstractWorkflow;

class AbstractPriceGroup extends AbstractWorkflow
{
    protected function eventIndexerReturn()
    {
        $priceGroup = new \stdClass();
        $priceGroup->name = 'priceGroup';
        $priceGroup->id = 14;

        $channelIds = new \stdClass();
        $channelIds->name = 'channelIds';
        $channelIds->id = 12;

        $event = new \stdClass();
        $event->params = [$priceGroup, $channelIds];
        $event->id = 2;
        return $event;
    }

    protected function listenerIndexerReturn()
    {
        $appRegistryId = new \stdClass();
        $appRegistryId->name = 'appRegistryId';
        $appRegistryId->id = 3;

        $uri = new \stdClass();
        $uri->name = 'uri';
        $uri->id = 4;

        $data = new \stdClass();
        $data->name = 'data';
        $data->id = 4;

        $listener = new \stdClass();
        $listener->params = [$appRegistryId, $uri, $data];
        $listener->id = 1;
        return $listener;
    }
}