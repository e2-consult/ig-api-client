<?php

namespace E2Consult\IGApi\Traits;

use Illuminate\Support\Carbon;

trait OrdersTrait
{
    public function getOrders()
    {
        return collect($this->sendGetRequest('/gateway/deal/workingorders', ['VERSION' => 2])->workingOrders);
    }

    public function createOrder()
    {
        /*
        [Constraint: If guaranteedStop equals true, then set only one of stopDistance]
        [Constraint: If timeInForce equals GOOD_TILL_DATE, then set goodTillDate]
        [Constraint: Set only one of {limitLevel,limitDistance}]
        [Constraint: Set only one of {stopLevel,stopDistance}]

        Expiry:
        The date (and sometimes time) at which a spreadbet or CFD will automatically close against some predefined market value should the bet remain open beyond its last dealing time. Some CFDs do not expire, and have an expiry of '-'. eg DEC-14, or DFB for daily funded bets.


        */

        return $this->sendPostRequest('/gateway/deal/workingorders', ['VERSION' => 2], [
            'currencyCode' => 'USD',
            'dealReference' => 'My name',
            'direction' => 'SELL',
            'epic' => 'CS.D.LTCUSD.CFD.IP',
            'expiry' => '-',
            // 'forceOpen' => '',
            'goodTillDate' => function () {
                return Carbon::now()->addDays(2)->format('Y-m-d H:i:s');
            },
            'guaranteedStop' => false,
            'level' => 58.5,
            // 'limitDistance' => '',
            // 'limitLevel' => '',
            'size' => 1,
            // 'stopDistance' => '',
            // 'stopLevel' => '',
            'timeInForce' => 'GOOD_TILL_CANCELLED',
            'type' => 'STOP',
        ]);
    }
}
