<?php
namespace App;

use GuzzleHttp\Client;

class RfidClient
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function getMembers()
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://midsouthmakers-rfid.app',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $response = $client->request('GET', '/api/members', [
            'headers' => [
                'Authorization' => $this->token,
            ]
        ]);

        return \GuzzleHttp\json_decode((string) $response->getBody(), true);
    }
}