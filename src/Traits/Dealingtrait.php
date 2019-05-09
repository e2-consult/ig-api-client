<?php

namespace E2Consult\IGApi\Traits;

trait DealingTrait
{
    public function getDealConfirmation($dealReference)
    {
        return $this->sendGetRequest('confirms/'.$dealReference);
    }

    public function getOpenPositions()
    {
        return $this->sendGetRequest('positions', 2)->positions;
    }

    public function getOpenPosition($dealId)
    {
        return $this->sendGetRequest('positions/'.$dealId, 2);
    }

    public function setPosition(array $options = [
        'currencyCode' => null,
        'dealReference' => null,
        'direction' => null,
        'epic' => null,
        'expiry' => null,
        'forceOpen' => null,
        'guaranteedStop' => null,
        'level' => null,
        'limitDistance' => null,
        'limitLevel' => null,
        'orderType' => null,
        'quoteId' => null,
        'size' => null,
        'stopDistance' => null,
        'stopLevel' => null,
        'timeInForce' => null,
        'trailingStop' => null,
    ])
    {
        return $this->sendPostRequest('positions/otc', 2);
    }

    public function updatePosition(string $dealId, array $options = [
        'limitLevel' => null,
        'stopLevel' => null,
        'trailingStop' => null,
        'trailingStopDistance' => null,
        'trailingStopIncrement' => null,
    ])
    {
        return $this->sendPutRequest('positions/otc/'.$dealId, 2, $options);
    }

    public function deletePosition(array $options = [
        'dealId' => null,
        'direction' => null,
        'epic' => null,
        'expiry' => null,
        'level' => null,
        'orderType' => null,
        'quoteId' => null,
        'size' => null,
        'timeInForce' => null,
    ])
    {
        return $this->sendDeleteRequest('positions/otc', 2);
    }

    public function getOpenSprintMarketPositions()
    {
        return $this->sendGetRequest('positions/sprintmarkets', 2)->sprintMarketPositions;
    }

    public function setSprintMarketPositions(array $options = [
        'dealReference' => null,
        'direction' => null,
        'epic' => null,
        'expiryPeriod' => null,
        'size' => null,
    ])
    {
        return $this->sendPostRequest('positions/sprintmarkets', 1, $options);
    }

    public function getOpenWorkingOrders()
    {
        return $this->sendGetRequest('workingorders', 2)->workingOrders;
    }

    public function setWorkingOrder(array $options = [
        'currencyCode' => null,
        'dealReference' => null,
        'direction' => null,
        'epic' => null,
        'expiry' => null,
        'forceOpen' => null,
        'goodTillDate' => null,
        'guaranteedStop' => null,
        'level' => null,
        'limitDistance' => null,
        'limitLevel' => null,
        'size' => null,
        'stopDistance' => null,
        'stopLevel' => null,
        'timeInForce' => null,
        'type' => null,
    ])
    {
        return $this->sendPostRequest('workingorders/otc', 2);
    }

    public function updateWorkingOrder(string $dealId, array $options = [
        'goodTillDate' => null,
        'level' => null,
        'limitDistance' => null,
        'limitLevel' => null,
        'stopDistance' => null,
        'stopLevel' => null,
        'timeInForce' => null,
        'type' => null,
    ])
    {
        return $this->sendPutRequest('workingorders/otc/'.$dealId, 2, $options);
    }

    public function deleteWorkingOrder(string $dealId)
    {
        return $this->sendDeleteRequest('workingorders/otc/'.$dealId, 2);
    }
}
