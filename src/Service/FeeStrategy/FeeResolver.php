<?php

declare(strict_types=1);

namespace App\Service\FeeStrategy;

use App\Transaction;

class FeeResolver
{
    /**
     * @var FeeApplicableInterface[]
     */
    private iterable $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies;
    }

    public function resolve(Transaction $transaction): Transaction
    {
        /** @var FeeApplicableInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->isApplicable($transaction)) {
                return $strategy->applyFee($transaction);
            }
        }

        //        $transaction->errors[] = 'Can\'t choose the right Fee strategy for transaction, check errors above.';

        return $transaction;
    }
}
