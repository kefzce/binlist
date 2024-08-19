<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Country;
use App\Transaction;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class BinlistProvider
{
    private ?LoggerInterface $logger = null;
    private string $url;

    public function __construct(
        private ClientInterface $client,
        string $url,
        ?Logger $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();
        $this->url = $url;
    }

    public function lookup(Transaction $transaction): ?Transaction
    {
        $this->logger->debug('Lookup transaction request', ['transaction' => $transaction]);

        try {
            $response = $this->client->request('GET', $this->url.$transaction->bin);
            $jsonData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException|GuzzleException|\JsonException|RequestException $e) {
            $this->logger->debug('Got exception', ['exception' => $e->getMessage()]);
            $transaction->errors[] = $e->getMessage();

            return $transaction;
        }

        $this->logger->debug('Lookup transaction response', ['response' => $jsonData]);

        return $transaction->withCountry(new Country($jsonData['country']['alpha2']));
    }
}
