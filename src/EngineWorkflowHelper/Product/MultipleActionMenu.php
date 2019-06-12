<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Product;

use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractActionMenu;
use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractMultipleActionMenu;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;
use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Enums\VirtualEntity;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionApproach;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionOperator;

class MultipleActionMenu extends AbstractMultipleActionMenu
{
    /**
     * @var string
     */
    protected $entity = VirtualEntity::ITEM_PRICE;
    /**
     * @var string
     */
    protected $endpoint = 'products/action-menu';
}