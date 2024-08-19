<?php

declare(strict_types=1);

namespace App\Command\Chain;

use App\Service\FeeStrategy\FeeResolver;
use App\Transaction;

class FeeResolutionCommandChain implements TransactionCommandChainInterface
{
    public function __construct(private FeeResolver $feeResolver) {}

    public function handle(Transaction $transaction): Transaction
    {
        return $this->feeResolver->resolve($transaction);
    }

    public function priority(): int
    {
        return 1;
    }
}
