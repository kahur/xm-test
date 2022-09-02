<?php

namespace App\Http\Request\RapidApi;

use App\Http\RequestInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FinanceHistoryRequest implements RequestInterface
{
    const HTTP_REQUEST = 'GET';
    const RAPID_HOST = 'yh-finance.p.rapidapi.com';

    public function __construct(
        private string $rapidApiHost,
        private string $rapidApiKey,
        private HttpClientInterface $httpClient
    ) { }

    /**
     * @param array $data
     * @param array $options
     * @return string|void
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function execute(array $data = [], array $options = []): string
    {
        $headers = $options['headers'] ?? [];

        $headers['X-RapidAPI-Key'] = $this->rapidApiKey;
        $headers['X-RapidAPI-Host'] = self::RAPID_HOST;

        $url = $this->rapidApiHost . '?' . http_build_query($data);

        $response = $this->httpClient->request(self::HTTP_REQUEST, $url, [
            'headers' => $headers
        ]);

        return $response->getContent();
    }
}
