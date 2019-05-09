<?php

namespace E2Consult\IGApi\Traits;

trait AccountTrait
{
    public function getAccounts()
    {
        return $this->sendGetRequest('accounts')->accounts;
    }

    public function setActiveAccount(array $options = [
        'accountId' => null,
        'defaultAccount' => null,
    ])
    {
        return $this->sendPutRequest('session', 1, $options);
    }

    public function getAccountPreferences()
    {
        return $this->sendGetRequest('accounts/preferences');
    }

    public function setAccountPreferences(array $options = ['trailingStopsEnabled' => true])
    {
        return $this->sendPutRequest('accounts/preferences', 1, $options);
    }

    public function getAccountActivity(array $options = [
        'from' => null,
        'to' => null,
        'detailed' => false,
        'dealId' => '',
        'filter' => null,
        'pageSize' => 50,
    ])
    {
        $options['from'] = $options['from'] ?? now()->startOfYear()->toDateString();

        return $this->sendGetRequest('history/activity', 3, $options);
    }

    public function getTransactionHistory(array $options = [
        'type' => 'ALL',
        'from' => null,
        'to' => null,
        'pageSize' => 0,
    ])
    {
        $options['from'] = $options['from'] ?? now()->startOfYear()->toDateString();

        return $this->sendGetRequest('history/transactions', 2, $options);
    }
}
