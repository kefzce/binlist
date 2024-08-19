<?php

declare(strict_types=1);

namespace App\Command\Chain;

use App\Service\Provider\ExchangeRatesProvider;
use App\Transaction;

class ExchangeRateCommandChain implements TransactionCommandChainInterface
{
    public function __construct(private readonly ExchangeRatesProvider $exchangeRatesProvider) {}

    public function handle(Transaction $transaction): Transaction
    {
        return $this->exchangeRatesProvider->provide($transaction);
    }

    public function priority(): int
    {
        return 2;
    }
}
