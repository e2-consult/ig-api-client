<?php

namespace E2Consult\IGApi\Traits;

trait LoginTrait
{
    public function getSessionDetails()
    {
        return $this->sendGetRequest('session');
    }

    public function deleteSession()
    {
        return $this->sendDeleteRequest('session');
    }
}
