<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Traits;

trait SetEventByName
{
    /**
     * @override
     */
    protected function setEvent()
    {
        $events = $this->eventIndexer
            ->setQuery([
                'name' => self::EVENT_NAME,
                '_with' => 'params'
            ])
            ->setLimit(1)
            ->retrieve();

        if ($events->isEmpty()) {
            throw new \RuntimeException('Engine Events not found.');
        }
        $this->event = $events->first();
    }
}