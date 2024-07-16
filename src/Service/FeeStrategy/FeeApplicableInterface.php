<?php

declare(strict_types=1);

namespace App\Service\FeeStrategy;

use App\Transaction;

interface FeeApplicableInterface
{
    public function isApplicable(Transaction $transaction): bool;

    public function applyFee(Transaction $transaction): Transaction;
}
