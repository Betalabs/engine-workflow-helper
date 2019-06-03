<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Traits;

use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Structure\Retrieval as StructureRetrieval;
use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\VirtualEntity\Retrieval as VirtualEntityRetrieval;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


trait SetOrderStatusParameters
{
    /**
     * Set formID, extraFieldId and order status parameters
     */
    protected function setOrderStatusParameters(): void
    {
        /**
         * @var $structureRetrieval \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\VirtualEntity\Retrieval
         */
        $structureRetrieval = resolve(StructureRetrieval::class);

        /**
         * @var $virtualEntityRetrieval \Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Structure\Retrieval
         */
        $virtualEntityRetrieval = resolve(VirtualEntityRetrieval::class);

        $orderStructure = $structureRetrieval
            ->setEndpoint("orders")
            ->setQuery(['data' => 'mapping'])
            ->retrieve();

        $orderStatusKey = 'order-status';
        if (empty($orderStructure['mapping']->$orderStatusKey->extra_field)) {
            throw new \RuntimeException(
                'Order Structure could not be retrieved.'
            );
        }

        $extraFields = $orderStructure['mapping']->$orderStatusKey->extra_field;

        $explodedKey = explode('_', $extraFields->key);

        $this->orderStatusFormId = $explodedKey[1];
        $this->orderStatusExtraFieldId = $explodedKey[2];

        $orderStatusVirtualEntity = $virtualEntityRetrieval
            ->setQuery(['slug' => 'order-statuses'])
            ->retrieve()
            ->first();

        $orderStatusVirtualEntityId = $orderStatusVirtualEntity->id;

        $virtualEntityRecords = $virtualEntityRetrieval
            ->setQuery([])
            ->setEndpoint($orderStatusVirtualEntityId . "/records")
            ->retrieve();

        $nameKey = $this->identifyNameKey($virtualEntityRecords);

        $this->virtualEntityRecordStatusId = $this->searchVirtualEntityRecordId($virtualEntityRecords, $nameKey);
    }

    /**
     * @param $dataArray
     * @return string
     */
    protected function identifyNameKey(Collection $dataArray): string
    {
        $key = $dataArray->reduce(function($initial, $status){
            foreach ((array)$status as $key => $value) {
                if(Str::contains($key,'nome')){
                    return $key;
                }
            }
        });

        if($key) {
            return $key;
        };

        throw new \RuntimeException(
            'Virtual entity record name field not found.'
        );
    }

    /**
     * @param $virtualEntityRecords
     * @param $nameKey
     * @return int
     */
    protected function searchVirtualEntityRecordId($virtualEntityRecords, $nameKey): int
    {
        foreach ($virtualEntityRecords as $virtualEntityRecord) {
            if ($virtualEntityRecord->$nameKey == $this->orderStatus) {
                return $virtualEntityRecord->real_id;
            }
        }
        throw new \RuntimeException(
            $this->orderStatus . ' Virtual entity record not found.'
        );
    }
}