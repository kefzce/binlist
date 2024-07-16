<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\FeeStrategy\FeeResolver;
use App\Transaction;

class FeeResolutionCommand implements TransactionCommandInterface
{
    public function __construct(private FeeResolver $feeResolver) {}

    public function execute(Transaction $transaction): Transaction
    {
        return $this->feeResolver->resolve($transaction);
    }

    public function priority(): int
    {
        return 1;
    }
}
