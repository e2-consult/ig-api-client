<?php

namespace E2Consult\IGApi;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;

class RequestHandler
{
    private $credentials;
    private $session;
    private $client_secret;

    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /*
     * What is placed inside the return function gets
     * runned everytime we call a function from the client
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $session = Session::restoreOrCreate($this->credentials);

            return $handler(
                $request->withAddedHeader('X-IG-API-KEY', $this->credentials->getApiKey())
                    ->withAddedHeader('IG-ACCOUNT-ID', $session->getAccountId())
                    ->withAddedHeader('Authorization', 'Bearer '.$session->getAccessToken()),
                $options
            );
        };
    }
}
