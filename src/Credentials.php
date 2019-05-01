<?php

namespace E2Consult\IGApi;

class Credentials
{
    private $username;
    private $password;
    private $apiKey;

    public function __construct($username, $password, $apiKey)
    {
        $this->username = $username;
        $this->password = $password;
        $this->apiKey = $apiKey;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function toBodyJson()
    {
        return json_encode([
            'identifier' => $this->getUsername(),
            'password' => $this->getPassword(),
        ]);
    }

    public function toArray()
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'apiKey' => $this->apiKey,
        ];
    }

    public function serialize()
    {
        return json_encode($this->toArray());
    }

    public function unserialize($serialized)
    {
        $data = json_decode($serialized, true);

        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->apiToken = $data['apiToken'];
    }
}
