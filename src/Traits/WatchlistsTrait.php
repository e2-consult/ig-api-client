<?php

namespace E2Consult\IGApi\Traits;

use Illuminate\Support\Facades\Cache;

trait WatchlistsTrait
{
    public function showWatchlist($name = null)
    {
        if (is_null($name)) {
            return $this->getAllWatchlists();
        }

        return $this->getSpecificWatchlistDetailed($this->getSpecificWatchlist($name)->id);
    }

    public function createWatchlist($name = null, $epics = [])
    {
        if (is_null($name) || count($epics) === 0) {
            return null;
        }

        $watchlist = $this->sendPostRequest('/gateway/deal/watchlists', [], [
            'name' => $name,
            'epics' => $epics,
        ]);

        if ($watchlist->status === 'SUCCESS') {
            $this->forget('watchlists.all');

            return $this->getSpecificWatchlistDetailed($watchlist->watchlistId);
        }

        return $watchlist->message;
    }

    public function addMarketToWatchlist($name = null, $epic = null)
    {
        if (is_null($name) || is_null($epic)) {
            return null;
        }

        $watchlist = $this->getSpecificWatchlist($name);

        $response = $this->sendPutRequest("/gateway/deal/watchlists/{$watchlist->id}", [], [
            'epic' => $epic,
        ]);

        if ($response->status === 'SUCCESS') {
            return $this->getSpecificWatchlistDetailed($watchlist->id);
        }

        return false;
    }

    public function removeMarketFromWatchlist($name = null, $epic = null)
    {
        if (is_null($name) || is_null($epic)) {
            return null;
        }

        $watchlist = $this->getSpecificWatchlist($name);

        $response = $this->sendDeleteRequest("/gateway/deal/watchlists/{$watchlist->id}/{$epic}");

        return $this->returnBooleanResponse($response);
    }

    public function deleteWatchlist($name = null)
    {
        if (is_null($name)) {
            return null;
        }

        $watchlist = $this->getSpecificWatchlist($name);

        if (is_null($watchlist)) {
            return false;
        }

        $response = $this->sendDeleteRequest("/gateway/deal/watchlists/{$watchlist->id}");
        $this->forget('watchlists.all');

        return $this->returnBooleanResponse($response);
    }

    public function getAllWatchlists($reset = false)
    {
        if ($reset) {
            $this->forget('watchlists.all');
        }

        return Cache::remember('api.ig.watchlists.all', now()->addDay(), function () {
            return collect($this->sendGetRequest('/gateway/deal/watchlists')->watchlists);
        });
    }

    protected function getSpecificWatchlist($name = null, $reset = false)
    {
        if (is_null($name)) {
            return null;
        }

        return $this->getAllWatchlists($reset)->firstWhere('name', $name);
    }

    protected function getSpecificWatchlistDetailed($watchlistId = null)
    {
        if (is_null($watchlistId)) {
            return null;
        }

        return collect($this->sendGetRequest("/gateway/deal/watchlists/{$watchlistId}")->markets);
    }
}
