<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AppDispatcher;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractAppDispatcherWorkflow;
use Illuminate\Support\Collection;

abstract class Post extends AbstractAppDispatcherWorkflow
{
    const LISTENER_METHOD = 'post';

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