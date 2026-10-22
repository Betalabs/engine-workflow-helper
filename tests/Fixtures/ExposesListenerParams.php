<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Fixtures;

use Illuminate\Support\Collection;

/**
 * Minimal implementation of the AppDispatcher abstract methods, exposing
 * listenerParams() for the tests
 */
trait ExposesListenerParams
{
    protected function eventParams(): Collection
    {
        return collect([]);
    }

    protected function createStepParams(): array
    {
        return [];
    }

    public function exposedListenerParams(): Collection
    {
        return $this->listenerParams();
    }
}
