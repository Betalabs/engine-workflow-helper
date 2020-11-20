<?php

namespace Betalabs\EngineWorkflowHelper\Traits;

use Betalabs\EngineWorkflowHelper\Structure\Retrieval as StructureRetrieval;
use Betalabs\EngineWorkflowHelper\VirtualEntity\Retrieval as VirtualEntityRetrieval;
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
         * @var $structureRetrieval \Betalabs\EngineWorkflowHelper\VirtualEntity\Retrieval
         */
        $structureRetrieval = resolve(StructureRetrieval::class);

        /**
         * @var $virtualEntityRetrieval \Betalabs\EngineWorkflowHelper\Structure\Retrieval
         */
        $virtualEntityRetrieval = resolve(VirtualEntityRetrieval::class);

        $orderStructure = $structureRetrieval
            ->setEndpoint("orders")
            ->setQuery(['data' => 'mapping'])
            ->retrieve();

        $orderStatusKey = 'order-status';
        if (empty($orderStructure['mapping']->extra_field->$orderStatusKey)) {
            throw new \RuntimeException(
                'Order Structure could not be retrieved.'
            );
        }

        $extraFields = $orderStructure['mapping']->extra_field->$orderStatusKey;

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
            if (isset($virtualEntityRecord->$nameKey) && $virtualEntityRecord->$nameKey == $this->orderStatus) {
                return $virtualEntityRecord->real_id;
            }
        }
        throw new \RuntimeException(
            $this->orderStatus . ' Virtual entity record not found.'
        );
    }
}
