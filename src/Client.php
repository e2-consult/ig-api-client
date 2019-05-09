<?php

namespace E2Consult\IGApi;

class Client
{
    use Traits\AccountTrait,
        Traits\DealingTrait,
        Traits\HelpersTrait,
        Traits\LoginTrait,
        Traits\MarketsTrait,
        Traits\PricesTrait,
        Traits\StreamingTrait,
        Traits\WatchlistsTrait;

    public $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * Send a GET Request to the IG API
     * @param   string      $url        Url to which the request is to be sent
     * @param   array       $version       Headers that is to be sent with the request
     */
    public function sendGetRequest($url, $version = null, $params = null)
    {
        return $this->sendRequest('GET', $url, $version, null, $params);
    }

    /**
     * Send a POST Request to the IG API
     *
     * @param   string      $url        Url to which the request is to be sent
     * @param   array       $version       Headers that is to be sent with the request
     * @param   array       $body       Data payload that is to be sent with the request
     * @return  stdClass
     */
    public function sendPostRequest($url, $version = null, $body = [])
    {
        return $this->sendRequest('POST', $url, $version, $body);
    }

    /**
     * Send a PUT Request to the IG API
     *
     * @param   string      $url        Url to which the request is to be sent
     * @param   array       $version       Headers that is to be sent with the request
     * @param   array       $body       Data payload that is to be sent with the request
     * @return  stdClass
     */
    protected function sendPutRequest($url, $version = null, $body = [])
    {
        return $this->sendRequest('PUT', $url, $version, $body);
    }

    /**
     * Send a DELETE Request to the IG API
     *
     * @param   string      $url        Url to which the request is to be sent
     * @param   array       $version       Headers that is to be sent with the request
     * @param   array       $body       Data payload that is to be sent with the request
     * @return  stdClass
     */
    protected function sendDeleteRequest($url, $version = null, $body = [])
    {
        return $this->sendRequest('DELETE', $url, $version, $body);
    }

    public function sendRequest($type = 'GET', $url = '/', $version = 1, $body = null, $params = null)
    {
        $options = array_merge([
            'headers' => [
                'VERSION' => $version,
            ],
        ],
            $body ? ['body' => json_encode($body)] : [],
            $params ? ['query' => $params] : [],
        );

        try {
            return json_decode(
                $this->service
                    ->request($type, '/gateway/deal/'.$url, $options)
                    ->getBody()
                    ->getContents()
            );
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statuscode = $response->getStatusCode();
            $message = json_decode($response->getBody()->getContents());

            return (object) [
                'status' => $statuscode,
                'message' => $message->errorCode,
            ];
        }
    }
}
