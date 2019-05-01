<?php

namespace E2Consult\IGApi;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Handler\CurlHandler;

class ClientFactory
{
    private $credentials;
    private $session;
    private $version;

    public static function createRestClient(Credentials $credentials): Client
    {
        $service = (new self)->setCredentials($credentials)
            ->create();

        return new Client($service);
    }

    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;

        return $this;
    }

    public function create()
    {
        $stack = HandlerStack::create(new CurlHandler());
        $stack->push(new RequestHandler(
            $this->credentials
        ));

        return new Guzzle([
            'base_uri' => 'https://demo-api.ig.com/',
            'handler' => $stack,
            'headers' => array_merge([
                'Accept' => 'application/json; charset=UTF-8',
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
                $this->version ? ['VERSION' => $this->version] : [],
            ),
        ]);
    }
}
