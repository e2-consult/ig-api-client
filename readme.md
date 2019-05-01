# IG Laravel API Client (Work in Progress)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/e2consult/ig-api-client.svg)](https://packagist.org/packages/e2consult/ig-api-client)
[![Code coverage](https://scrutinizer-ci.com/g/e2consult/ig-api-client/badges/coverage.png)](https://scrutinizer-ci.com/g/e2consult/ig-api-client)
[![Quality Score](https://img.shields.io/scrutinizer/g/e2consult/ig-api-client.svg)](https://scrutinizer-ci.com/g/e2consult/ig-api-client)
[![License](https://img.shields.io/packagist/l/e2consult/ig-api-client.svg)](https://packagist.org/packages/e2consult/ig-api-client)
[![Total Downloads](https://img.shields.io/packagist/dt/e2consult/ig-api-client.svg)](https://packagist.org/packages/e2consult/ig-api-client)
[![StyleCI](https://styleci.io/repos/181854402/shield)](https://styleci.io/repos/181854402)

E2Consult is a webdevelopment team based in Oslo, Norway. You'll find more information about us [on our website](https://e2consult.no).

This package is made to easily communicate with IGs API using PHP and Laravel, [read more about the API](https://labs.ig.com/rest-trading-api-guide).


## Installation

You can install the package via composer:

```bash
composer require e2consult/ig-api-client
```

Then you need to set your credentials in the .env file, and add the following array to your config/services.php file.

``` php
    'IG' => [
        'username'  => env('IG_CLIENT_ID'),
        'password'  => env('IG_CLIENT_SECRET'),
        'api_key'   => env('IG_API_KEY')
    ],
```

## Usage

To get going you only need to pass the relevant customer ID when creating the client.

``` php

    use E2Consult\IGApi\Facades\Client;

    // Accounts
    Client::getSessionInfo();
    Client::getAccountInfo();
    Client::getAccountPreferences();
    Client::getAccountActivity();
    Client::getTransactionHistory();
```

## License

The MIT License (MIT).
