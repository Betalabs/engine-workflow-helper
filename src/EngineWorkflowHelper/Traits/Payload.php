<?php

namespace Betalabs\EngineWorkflowHelper\Traits;

trait Payload
{
    /**
     * Remove keys with empty values
     *
     * @param array $payload
     *
     * @return array
     */
    private function removeEmpty(array $payload)
    {
        return array_filter($payload, function ($value) {
            return !empty($value);
        });
    }
}