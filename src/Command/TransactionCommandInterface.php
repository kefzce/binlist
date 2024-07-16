<?php

declare(strict_types=1);

namespace App\Command;

use App\Transaction;

interface TransactionCommandInterface
{
    public function execute(Transaction $transaction): Transaction;

    public function priority(): int;
}
