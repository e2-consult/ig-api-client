<?php

namespace E2Consult\IGApi;

class Client
{
    use Traits\AccountsTrait,
        Traits\PositionsTrait,
        Traits\MarketsTrait,
        Traits\OrdersTrait,
        Traits\PricesTrait,
        Traits\StreamingTrait,
        Traits\WatchlistsTrait,
        Traits\HelpersTrait;

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
    public function sendGetRequest($url, $version = null)
    {
        return $this->sendRequest('GET', $url, $version);
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

    public function sendRequest($type = 'GET', $url = '/', $version = 1, $body = null, $retryOnError = true, int $i = 0)
    {
        $options = array_merge([
            'headers' => [
                'VERSION' => $version,
            ],
        ],
            $body ? ['body' => json_encode($body)] : [],
        );

        try {
            return json_decode($this->service->request($type, $url, $options)->getBody()->getContents());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statuscode = $response->getStatusCode();
            $message = json_decode($response->getBody()->getContents());
            dd($message);
            // if ($retryOnError && $i < 3 && $statuscode === 401) {
            //     $this->service = (new Authenticate($this->username, $this->password, $this->apiToken))->createClient();

            //     return $this->sendRequest($type, $url, $headers, $body, $retryOnError, ++$i);
            // }
            // if ($retryOnError && $i < 3 && $statuscode === 400 && is_null($message)) {
            //     return $this->sendRequest($type, $url, $headers, $body, $retryOnError, ++$i);
            // }

            return (object) [
                'status' => $statuscode,
                'message' => $message->errorCode,
            ];
        }
    }
}
