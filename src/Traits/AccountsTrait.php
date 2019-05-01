<?php

namespace E2Consult\IGApi\Traits;

trait AccountsTrait
{
    public function getSessionInfo()
    {
        return $this->sendGetRequest('/gateway/deal/session');
    }

    public function getAccountInfo()
    {
        return collect($this->sendGetRequest('/gateway/deal/accounts')->accounts);
    }

    public function getAccountPreferences()
    {
        return $this->sendGetRequest('/gateway/deal/accounts/preferences');
    }

    public function getAccountActivity()
    {
        return $this->sendGetRequest('/gateway/deal/history/activity', 2);
    }

    public function getTransactionHistory()
    {
        return $this->sendGetRequest('/gateway/deal/history/transactions', 2);
    }
}
