<?php

declare(strict_types=1);

namespace App\Service\FeeStrategy;

use App\Country;
use App\Transaction;

class FixedValueFee implements FeeApplicableInterface
{
    public function isApplicable(Transaction $transaction): bool
    {
        return 'EUR' === $transaction->currency || 0 === $transaction->exchangeRate;
    }

    public function applyFee(Transaction $transaction): Transaction
    {
        $fee = $transaction->amount * ($transaction->countryOrigin->isEuBased ? Country::EU_BASED_TRANSACTION_COST : Country::NON_EU_BASED_TRANSACTION_COST);

        return $transaction->withFee($fee);
    }
}
