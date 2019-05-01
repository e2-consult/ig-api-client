<?php

namespace E2Consult\IGApi\Traits;

trait PositionsTrait
{
    public function showOpenPositions()
    {
        return collect($this->sendGetRequest('/gateway/deal/positions', 2)->positions);
    }

    public function createPosition()
    {
        /*
        [Constraint: If a limitDistance is set, then forceOpen must be true]
        [Constraint: If a limitLevel is set, then forceOpen must be true]
        [Constraint: If a stopDistance is set, then forceOpen must be true]
        [Constraint: If a stopLevel is set, then forceOpen must be true]
        [Constraint: If guaranteedStop equals true, then set only one of stopLevel,stopDistance]
        [Constraint: If orderType equals LIMIT, then DO NOT set quoteId]
        [Constraint: If orderType equals LIMIT, then set level]
        [Constraint: If orderType equals MARKET, then DO NOT set level,quoteId]
        [Constraint: If orderType equals QUOTE, then set level,quoteId]
        [Constraint: If trailingStop equals false, then DO NOT set trailingStopIncrement]
        [Constraint: If trailingStop equals true, then DO NOT set stopLevel]
        [Constraint: If trailingStop equals true, then guaranteedStop must be false]
        [Constraint: If trailingStop equals true, then set stopDistance,trailingStopIncrement]
        [Constraint: Set only one of {limitLevel,limitDistance}]
        [Constraint: Set only one of {stopLevel,stopDistance}]
        */
        return $this->sendPostRequest('/gateway/deal/positions/otc', 2, [
            'currencyCode' => 'USD',
            'dealReference' => 'MyReference',
            'direction' => 'SELL',
            'epic' => 'CS.D.LTCUSD.CFD.IP',
            'expiry' => '-',
            'forceOpen' => true,
            // 'goodTillDate' => function(){
            //     return Carbon::now()->addDays(2)->format('Y-m-d H:i:s');
            // },
            'guaranteedStop' => true,
            'level' => 58.5,
            // 'limitDistance' => '',
            'limitLevel' => 65,
            'orderType' => 'LIMIT',
            // 'quoteId' => ,
            'size' => 10,
            // 'stopDistance' => '',
            'stopLevel' => 50,
            'timeInForce' => 'EXECUTE_AND_ELIMINATE',
            'trailingStop' => false,
        ]);
    }
}
