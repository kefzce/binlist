<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Transaction;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ExchangeRatesProvider
{
    private ?LoggerInterface $logger = null;

    public function __construct(
        private ClientInterface $client,
        private ?string $accessKey = null,
        ?Logger $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();
    }

    public function provide(Transaction $transaction): Transaction
    {
        $this->logger->debug('Exchange rate request', ['transaction' => $transaction]);

        try {
            $raw = json_decode($response = $this->client->request('GET', 'http://api.exchangeratesapi.io/latest?access_key='.$this->accessKey)->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|\JsonException|RequestException $e) {
            $this->logger->debug('Got exception', ['exception' => $e->getMessage()]);
            $transaction->errors[] = $e->getMessage();
        }

        if (!empty($raw['error'])) {
            $transaction->errors[] = $raw['error']['info'];

            return $transaction;
        }

        $rate = $raw['rates'][$transaction->currency];

        return $transaction->withRate($rate ?? 0);
    }
}
