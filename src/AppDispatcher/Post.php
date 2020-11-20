<?php

namespace Betalabs\EngineWorkflowHelper\AppDispatcher;


use Betalabs\EngineWorkflowHelper\AbstractAppDispatcherWorkflow;
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
