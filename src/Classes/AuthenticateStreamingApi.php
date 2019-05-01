<?php

namespace E2Consult\IGApi\Classes;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class AuthenticateStreamingApi
{
    /**
    * @var string $accountId
    */
    protected $accountId;

    /**
    * @var string $request_id
    */
    protected $request_id;

    /**
    * @var string $cst
    */
    protected $cst;

    /**
    * @var string $security_token
    */
    protected $security_token;

    public function __construct($username, $password, $apiToken)
    {
        $this->username = $username;
        $this->password = $password;
        $this->apiToken = $apiToken;
    }

    public function cst()
    {
        return $this->cst;
    }

    public function securityToken()
    {
        return $this->security_token;
    }

    public function createClient()
    {
        $this->getOauthToken();

        return $this->buildClient();
    }

    public function getOauthToken()
    {
        if (is_null($this->request_id = Cache::get('api.ig.request_id'))) {
            return $this->login();
        }
        if (is_null($this->cst = Cache::get('api.ig.cst'))) {
            return $this->login();
        }
        if (is_null($this->security_token = Cache::get('api.ig.security_token'))) {
            return $this->refreshToken();
        }

        return true;
    }

    public function login($url = '', $version = 3)
    {
        $client = new Client([
            'base_uri' => 'https://demo-api.ig.com',
            'headers' => [
                'Accept' => 'application/json; charset=UTF-8',
                'Content-Type' => 'application/json; charset=UTF-8',
                'VERSION' => 3,
                'X-IG-API-KEY' => $this->apiToken,
            ],
        ]);

        $response = $client->request('POST', '/gateway/deal/session?fetchSessionTokens=true'.$url, [
            'headers' => [
                'VERSION' => 2,
            ], 'body' => json_encode([
                'identifier' => $this->username,
                'password' => $this->password,
            ]),
        ]);

        $headers = $response->getHeaders();

        $this->cst = $headers['CST'][0];
        $this->security_token = $headers['X-SECURITY-TOKEN'][0];
        $this->request_id = $headers['X-REQUEST-ID'][0];

        return $this;
    }

    // public function storeSessionCredentialsInCache($access_token, $refresh_token, $accountId = null, $stream_enpoint = null)
    // {
    //     if($accountId){
    //         Cache::forever('api.ig.accountId', $accountId);
    //     } elseif (is_null(Cache::get('api.ig.accountId'))){
    //         return $this->login();
    //     }
    //     if($stream_enpoint){
    //         Cache::forever('api.ig.stream_enpoint', $stream_enpoint);
    //     }
    //     $this->accountId = Cache::get('api.ig.accountId');
    //     Cache::put('api.ig.access_token', $this->access_token = $access_token, 1);
    //     Cache::put('api.ig.refresh_token', $refresh_token, 10);
    //     return true;
    // }
}
