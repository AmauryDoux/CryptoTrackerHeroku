<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface ;

class HttpClientService
{
    private $client;

    public function __construct(HttpClientInterface  $client)
    {
        $this->client = $client;
    }

    public function fetchCryptos(): array
    {

        // Fetch @ https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest to get id, price, name, symbol, percent_change_1h, percent_change_24, percent_change_7d
        // Fetch @ https://pro-api.coinmarketcap.com/v1/cryptocurrency/info to get logo
        // Try to link both results into 1 obj by linking them to their ID, eg: BTC = id 1 in both routes.
        // Then send that obj into front for treatment

        // $infoResponse = $this->client->request(
        //     'GET',
        //     'https://pro-api.coinmarketcap.com/v1/cryptocurrency/info',
        //     [
        //         'headers' => [
        //             'Content-Type' => 'text/plain',
        //             'X-CMC_PRO_API_KEY' => '1cbb9a8a-7e08-4629-bccf-aa6aa8541920'
        //         ],
        //         'query' => [
        //             'id' => '1'
        //         ]
        // ]);

        // $statusCode = $infoResponse->getStatusCode();
        // // $statusCode = 200
        // $contentType = $infoResponse->getHeaders()['content-type'][0];
        // // $contentType = 'application/json'
        // $infoContent = $infoResponse->getContent();
        // // $content = '{"id":521583, "name":"symfony-docs", ...}'
        // $infoContent = $infoResponse->toArray();
        // // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        // var_dump($infoContent);

        $response = $this->client->request(
            'GET',
            'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest',
            [
                'headers' => [
                    'Content-Type' => 'text/plain',
                    'X-CMC_PRO_API_KEY' => '1cbb9a8a-7e08-4629-bccf-aa6aa8541920'
                ],
                'query' => [
                    'start' => '1',
                    'limit' => '10',
                    'convert' => 'EUR'
                ]
        ]);

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        // Go through both results arrays, make ids match and add logo from $infoResponse to $content

        return $content;
    }

    /**
     * 
     */
    public function getCryptoInfos($id)
    {
        $content = [];
        $response = $this->client->request(
            'GET',
            'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest',
            [
                'headers' => [
                    'Content-Type' => 'text/plain',
                    'X-CMC_PRO_API_KEY' => '1cbb9a8a-7e08-4629-bccf-aa6aa8541920'
                ],
                'query' => [
                    'id' => $id,
                    'convert' => 'EUR'
                ]
        ]);

        if(
            $response->getStatusCode() == 200 
            && ($content = $response->toArray())
            && isset($content['data'][$id]['name'])
            && isset($content['data'][$id]['symbol'])
            && isset($content['data'][$id]['quote']['EUR']['price'])
        ) {
            $content = [
            'name' => $content['data'][$id]['name'],
            'symbol' => $content['data'][$id]['symbol'],
            'price' => $content['data'][$id]['quote']['EUR']['price']
            ];
        }
        // Go through both results arrays, make ids match and add logo from $infoResponse to $content
        return $content;

    }

    public function getCryptoById($id) {
        return [
                'id' => $id,
                'name' => "Bitcoin",
                'accro' => "BTC",
                'price' => "456",
                'profitability' => 1,
                'img' =>  "https://s2.coinmarketcap.com/static/img/coins/64x64/1.png"
        ];
    }

    public function getAllCryptos() 
    {
        $cryptos = $this->fetchCryptos();
        if(isset($cryptos['data'])) {
            return $cryptos['data'];
        }
        return [];
    }

    public function deleteCrypto($cryptoId) {
        $json  = [
            'response' => "deleted",
            'status' => true
        ];

        return json_encode($json);
    }

    public function addCrypto($crypto) {
        $json  = [
            'response' => "added",
            'status' => true
        ];

        var_dump($crypto);die;

        return json_encode($json);
    }

    public function buyCrypto($cryptoId, $amount, $price) {
        $json  = [
            'response' => "bought",
            'status' => true
        ];

        return json_encode($json);
    }

    public function sellCrypto($cryptoId, $amount, $price) {
        $json  = [
            'response' => "sold",
            'status' => true
        ];

        return json_encode($json);
    }

    public function getHoldings() {
        $json  = [
            'amount' => "2500"
        ];

        return json_encode($json);
    }
}
