<?php

declare(strict_types=1);

namespace App\Test;

use App\Command\Chain\BinLookupCommandChain;
use App\Command\Chain\ExchangeRateCommandChain;
use App\Command\Chain\FeeResolutionCommandChain;
use App\Command\Chain\TransactionCommandInvoker;
use App\Service\FeeStrategy\FeeRateApplier;
use App\Service\FeeStrategy\FeeResolver;
use App\Service\FeeStrategy\FixedValueFee;
use App\Service\Provider\BinlistProvider;
use App\Service\Provider\ExchangeRatesProvider;
use App\Transaction;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class TransactionChainTest extends TestCase
{
    public function testTransaction(): void
    {
        $client = $this->binLookupClient();
        $exchangeRateClient = $this->exchangeRatesClient();
        $commands = [new BinLookupCommandChain(new BinlistProvider($client, 'https://lookup.binlist.net/')),
            new ExchangeRateCommandChain(new ExchangeRatesProvider($exchangeRateClient, 'https://api.exchangeratesapi.io/latest', 's33cret')),
            new FeeResolutionCommandChain(
                new FeeResolver([
                    new FeeRateApplier(),
                    new FixedValueFee(),
                ])
            ),
        ];
        $command = new TransactionCommandInvoker($commands);

        $transaction = $command->execute(new Transaction(123, 100.00, 'USD'));
        self::assertSame(123, $transaction->bin);
        self::assertSame(100.00, $transaction->amount);
        self::assertSame('USD', $transaction->currency);
        self::assertSame($transaction->countryOrigin->country, 'DK');
        self::assertTrue($transaction->countryOrigin->isEuBased);
        self::assertEmpty($transaction->errors);
        self::assertSame(0.810_399_040_487_536_2, $transaction->transactionCost);
    }

    private static function binLookupSuccessResponse(): string
    {
        return <<<'JSON'
            {"number":{},"scheme":"visa","type":"debit","brand":"Visa Classic","country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"🇩🇰","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank A/S"}}
            JSON;
    }

    private function binLookupClient(): Client
    {
        $mock = new MockHandler([
            new Response(200, [], self::binLookupSuccessResponse()),
        ]);

        $handlerStack = HandlerStack::create($mock);

        return new Client(['handler' => $handlerStack]);
    }

    private static function exchangeRatesSuccessResponse(): string
    {
        return '
            {
    "success": true,
    "timestamp": 1519296206,
    "base": "EUR",
    "date": "2021-03-17",
    "rates": {
        "AUD": 1.566015,
        "CAD": 1.560132,
        "CHF": 1.154727,
        "CNY": 7.827874,
        "GBP": 0.882047,
        "JPY": 132.360679,
        "USD": 1.23396
    }
}';
    }

    private function exchangeRatesClient(): Client
    {
        $mock = new MockHandler([
            new Response(200, [], self::exchangeRatesSuccessResponse()),
        ]);

        $handlerStack = HandlerStack::create($mock);

        return new Client(['handler' => $handlerStack]);
    }
}
