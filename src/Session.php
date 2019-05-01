<?php

namespace E2Consult\IGApi;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Facades\Cache;

class Session
{
    private $clientId;
    private $accountId;
    private $accessToken;
    private $refreshToken;

    public static $cacheSessionKey = 'api.ig.session';
    public static $cacheRefreshTokenKey = 'api.ig.refresh_token';

    public function __construct($session = null)
    {
        if ($session) {
            $this->clientId = $session->clientId;
            $this->accountId = $session->accountId;
            $this->accessToken = $session->oauthToken->access_token;
            $this->refreshToken = $session->oauthToken->refresh_token;
        }
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function getHeaders()
    {
        return [
            'IG-ACCOUNT-ID' => $this->getAccountId(),
            'Authorization' => 'Bearer '.$this->getAccessToken(),
        ];
    }

    public function toArray()
    {
        return [
            'clientId' => $this->clientId,
            'accountId' => $this->accountId,
            'accessToken' => $this->accessToken,
            'refreshToken' => $this->refreshToken,
        ];
    }

    public function serialize()
    {
        return json_encode($this->toArray());
    }

    public function unserialize($serialized)
    {
        $data = json_decode($serialized, true);

        $this->clientId = $data['clientId'];
        $this->accountId = $data['accountId'];
        $this->accessToken = $data['accessToken'];
        $this->refreshToken = $data['refreshToken'];
    }

    public static function restoreOrCreate(Credentials $credentials)
    {
        if ($session = Cache::get(self::$cacheSessionKey)) {
            return new self($session);
        }
        if ($oldSession = Cache::get(self::$cacheRefreshTokenKey)) {
            return self::refreshToken($credentials, $oldSession);
        }

        return self::create($credentials);
    }

    public static function refreshToken(Credentials $credentials, $oldSession)
    {
        $oldSession->oauthToken = json_decode(static::client($credentials, 1)
            ->request('POST', '/gateway/deal/session/refresh-token', [
                'body' => json_encode([
                    'refresh_token' => $oldSession->oauthToken->refresh_token,
                ]),
            ])->getBody());
        static::storeSessionInCache($session = $oldSession);

        return new self($session);
    }

    public static function create(Credentials $credentials)
    {
        $response = json_decode(static::client($credentials)->request('POST', '/gateway/deal/session', [
            'body' => $credentials->toBodyJson(),
        ])->getBody()->getContents());
        static::storeSessionInCache($response);

        return new self($response);
    }

    public static function client(Credentials $credentials, $version = 3)
    {
        return new Guzzle([
            'base_uri' => 'https://demo-api.ig.com/',
            'headers' => [
                'Accept' => 'application/json; charset=UTF-8',
                'Content-Type' => 'application/json; charset=UTF-8',
                'VERSION' => $version,
                'X-IG-API-KEY' => $credentials->getApiKey(),
            ],
        ]);
    }

    public static function storeSessionInCache($session)
    {
        Cache::put(self::$cacheSessionKey, $session, (int) $session->oauthToken->expires_in);
        Cache::put(self::$cacheRefreshTokenKey, $session, 600);
    }
}
