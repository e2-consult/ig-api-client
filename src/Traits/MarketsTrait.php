<?php

namespace E2Consult\IGApi\Traits;

use Illuminate\Support\Facades\Cache;

trait MarketsTrait
{
    public function showMarketCategory($name = null, $second = null)
    {
        if (is_null($name)) {
            return $this->getMarketCategories();
        }
        if (is_null($second)) {
            return $this->getSpecificMarketCategory($name);
        }

        return $this->getSpecificMarketCategory($name, $second);
    }

    public function showMarketCategoryById($id = null)
    {
        if (is_null($id)) {
            return $this->getMarketCategories();
        }

        return Cache::remember('api.ig.markets.nodes.'.$id, 60 * 24 * 7, function () use ($id) {
            $response = $this->sendGetRequest("/gateway/deal/marketnavigation/{$id}");
            if (is_null($response->nodes)) {
                return collect($response->markets);
            }

            return collect($response->nodes);
        });
    }

    protected function getMarketCategories($reset = false)
    {
        if ($reset) {
            $this->forget('markets.nodes.all');
        }

        return Cache::remember('api.ig.markets.nodes.all', 60 * 24 * 7, function () {
            return collect($this->sendGetRequest('/gateway/deal/marketnavigation')->nodes);
        });
    }

    protected function getSpecificMarketCategory($name = null, $second = null)
    {
        if (is_null($name)) {
            return null;
        }
        if (is_null($second)) {
            return Cache::remember('api.ig.markets.nodes.'.str_slug($name), 60 * 24 * 7, function () use ($name) {
                $id = $this->getMarketCategories()
                    ->firstWhere('name', $name)
                    ->id;

                return collect($this->sendGetRequest("/gateway/deal/marketnavigation/{$id}")->nodes);
            });
        }

        return Cache::remember('api.ig.markets.nodes.'.str_slug($name.'-'.$second), 60 * 24, function () use ($name, $second) {
            $subnodes = $this->getSpecificMarketCategory($name);
            $subnode = $subnodes->firstWhere('name', $second);

            if (is_null($subnode)) {
                return [];
            }

            return collect($this->sendGetRequest("/gateway/deal/marketnavigation/{$subnode->id}")->nodes);
        });
    }

    public function showMarket($epics = [])
    {
        if (count($epics) == 0) {
            return $this->showMarketCategory();
        }

        return $this->getMarkets($epics);
    }

    protected function getMarket(string $epic = null)
    {
        if (is_null($epic)) {
            return $this->showMarketCategory();
        }

        return $this->sendGetRequest("/gateway/deal/markets/{$epic}", ['VERSION' => 3]);
    }

    protected function getMarkets(array $epics = [])
    {
        $epics = 'epics='.implode(',', $epics);

        return collect($this->sendGetRequest("/gateway/deal/markets?{$epics}", ['VERSION' => 2])->marketDetails);
    }
}
