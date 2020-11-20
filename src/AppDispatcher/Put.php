<?php

namespace Betalabs\EngineWorkflowHelper\AppDispatcher;


use Betalabs\EngineWorkflowHelper\AbstractAppDispatcherWorkflow;
use Illuminate\Support\Collection;

abstract class Put extends AbstractAppDispatcherWorkflow
{
    const LISTENER_METHOD = 'put';

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function listenerParams(): Collection
    {
        return parent::listenerParams()->merge(collect([
            'data',
        ]));
    }
}
