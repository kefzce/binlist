<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Transaction;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ExchangeRatesProvider
{
    private ?LoggerInterface $logger;

    public function __construct(
        private ClientInterface $client,
        private readonly string $url,
        private ?string $accessKey = null,
        ?Logger $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();
    }

    public function provide(Transaction $transaction): Transaction
    {
        $this->logger->debug('Exchange rate request', ['transaction' => $transaction]);

        if (null === $this->accessKey) {
            $this->logger->warning('Exchange rate access key required, no rates applied.', ['transaction' => $transaction]);

            $transaction->errors[] = 'Exchange rate access key required, no rates applied.';

            return $transaction;
        }

        try {
            $raw = json_decode($this->client->request('GET', $this->url.'?access_key='.$this->accessKey)->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException|GuzzleException|\JsonException|RequestException $e) {
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
