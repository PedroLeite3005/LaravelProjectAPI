<?php

namespace App\Models\Api;

use GuzzleHttp\Client;

class MercadoLivreAPI 
{
    private string $urlBase = '';

    public function __construct()
    {
        $this->urlBase = 'https://api.mercadolibre.com/';
    }

    protected function makeRequest(string $method, string $url, array $options)
    {
        try {
            $client = new Client([
                'base_uri' => $this->urlBase,
                'http_errors' => false,
                'timeout' => 60
            ]);

            return $client->request($method, $url, $options);
        } catch (\Exception $e) {
            return $this->getFakeResponse([
                'method' => $method,
                'url' => $this->urlBase . $url,
                'options' => $options,
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function getFakeResponse($body)
    {
        return new class($body) {
            public function __construct(public $body) { }
            public function getStatusCode() { return 500; }
            public function getBody() { return json_encode($this->body); }
        };
    }

    public function post(string $url, array $options = [])
    {
        return $this->makeRequest('POST', $url, $options);
    }

    public function get(string $url, array $options = [])
    {
        return $this->makeRequest('GET', $url, $options);
    }

    public function delete(string $url, array $options = [])
    {
        return $this->makeRequest('DELETE', $url, $options);
    }

    public function put(string $url, array $options = [])
    {
        return $this->makeRequest('PUT', $url, $options);
    }
}
