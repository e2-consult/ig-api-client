<?php

namespace E2Consult\IGApi\Facades;

use Illuminate\Support\Facades\Facade;

class Client extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ig-api-client';
    }
}
