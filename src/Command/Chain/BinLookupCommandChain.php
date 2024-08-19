<?php

declare(strict_types=1);

namespace App\Command\Chain;

use App\Service\Provider\BinlistProvider;
use App\Transaction;

class BinLookupCommandChain implements TransactionCommandChainInterface
{
    public function __construct(private readonly BinlistProvider $provider) {}

    public function handle(Transaction $transaction): Transaction
    {
        return $this->provider->lookup($transaction);
    }

    public function priority(): int
    {
        return 3;
    }
}
