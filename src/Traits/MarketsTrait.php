<?php

namespace E2Consult\IGApi\Traits;

trait MarketsTrait
{
    public function getMarketCategories()
    {
        return $this->sendGetRequest('marketnavigation');
    }

    public function getSubMarketCategories($nodeId)
    {
        return $this->sendGetRequest('marketnavigation/'.$nodeId);
    }

    public function getMarkets($query = null)
    {
        if (is_array($query)) {
            return $this->sendGetRequest('markets', 2, [
                'epics' => implode(',', $query),
            ]);
        }
        if (is_string($query)) {
            return $this->sendGetRequest('markets', 1, [
                'searchTerm' => $query,
            ]);
        }
    }

    public function getMarket(string $epic)
    {
        return $this->sendGetRequest('markets/'.$epic, 3);
    }

    public function getPrices(string $epic, array $options = [
        'resolution' => 'HOUR',
        'from' => null,
        'to' => null,
        'max' => '',
        'pageSize' => 0,
    ])
    {
        return $this->sendGetRequest('prices/'.$epic, 3, $options);
    }
}
