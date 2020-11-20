<?php

namespace Betalabs\EngineWorkflowHelper\Order;


use Betalabs\EngineWorkflowHelper\AbstractMultipleActionMenu;

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
