<?php

declare(strict_types=1);

namespace App\Service\FeeStrategy;

use App\Country;
use App\Transaction;

class FeeRateApplier implements FeeApplicableInterface
{
    public function isApplicable(Transaction $transaction): bool
    {
        return (null !== $transaction->currency && 'EUR' !== $transaction->currency) || $transaction->exchangeRate > 0;
    }

    public function applyFee(Transaction $transaction): Transaction
    {
        $fee = ($transaction->amount / $transaction->exchangeRate) * ($transaction->countryOrigin->isEuBased ? Country::EU_BASED_TRANSACTION_COST : Country::NON_EU_BASED_TRANSACTION_COST);

        return $transaction->withFee($fee);
    }
}
