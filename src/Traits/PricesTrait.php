<?php

namespace E2Consult\IGApi\Traits;

use Illuminate\Support\Carbon;

trait PricesTrait
{
    public function showPrices(string $epic = null, $resolution = 'MINUTE', $max = 10, $from = null, $to = null, $pageSize = 0, $page = null, $metadata = false)
    {
        if (is_null($epic)) {
            return null;
        }

        return $this->getPrices($epic, $resolution, $max, $from, $to, $pageSize, $page, $metadata);
    }

    protected function getPrices($epic, $resolution, $max, $from, $to, $pageSize, $page, $metadata)
    {
        $parameters = [
            'resolution' => $resolution,
            'pageSize' => $pageSize,
            'max' => $max,
        ];
        if ($from) {
            $parameters['from'] = Carbon::parse($from)->format('Y-m-d\TH:i:s');
        }
        if ($to) {
            $parameters['to'] = Carbon::parse($to)->format('Y-m-d\TH:i:s');
        }
        if ($page) {
            $parameters['pageNumber'] = $page;
        }

        $parameters = http_build_query($parameters);
        var_dump($parameters);
        $prices = $this->sendGetRequest("/gateway/deal/prices/{$epic}?{$parameters}", ['VERSION' => 3]);

        if ($metadata) {
            return collect($prices);
        }

        return collect($prices->prices);
    }
}
