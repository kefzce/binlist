<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Provider\BinlistProvider;
use App\Transaction;

class BinLookupCommand implements TransactionCommandInterface
{
    public function __construct(private readonly BinlistProvider $provider) {}

    public function execute(Transaction $transaction): Transaction
    {
        return $this->provider->lookup($transaction);
    }

    public function priority(): int
    {
        return 3;
    }
}
