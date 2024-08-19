<?php

declare(strict_types=1);

namespace App\Command\Chain;

use App\Transaction;

interface TransactionCommandChainInterface
{
    public function handle(Transaction $transaction): Transaction;

    public function priority(): int;
}
