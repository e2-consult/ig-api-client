<?php

namespace E2Consult\IGApi\Traits;

use Illuminate\Support\Facades\Cache;

trait HelpersTrait
{
    protected function returnBooleanResponse($response)
    {
        if ($response->status === 'SUCCESS') {
            return true;
        }

        return false;
    }

    protected function forget($name = null)
    {
        if (!is_null($name)) {
            Cache::forget('api.ig.'.$name);

            return true;
        }

        return false;
    }
}
