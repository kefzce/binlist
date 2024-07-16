<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Provider\ExchangeRatesProvider;
use App\Transaction;

class ExchangeRateCommand implements TransactionCommandInterface
{
    public function __construct(private readonly ExchangeRatesProvider $exchangeRatesProvider) {}

    public function execute(Transaction $transaction): Transaction
    {
        return $this->exchangeRatesProvider->provide($transaction);
    }

    public function priority(): int
    {
        return 2;
    }
}
