<?php

namespace App\Http\Services;

use GuzzleHttp\Client;

class RestClientService
{
    protected function getClient(): Client
    {
        return new Client(['connect_timeout' => 2]);
    }

    protected function getClientWithoutSSL(): Client
    {
        return new Client(['verify' => false, 'connect_timeout' => 2]);
    }

}
