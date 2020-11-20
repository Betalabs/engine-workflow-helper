<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractMultipleActionMenu;

class MultipleActionMenu extends AbstractMultipleActionMenu
{
    /**
     * @var string
     */
    protected $entity = 'order';
    /**
     * @var string
     */
    protected $endpoint = 'orders/action-menu';
}