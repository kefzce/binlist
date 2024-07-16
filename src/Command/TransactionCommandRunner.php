<?php

declare(strict_types=1);

namespace App\Command;

use App\Transaction;

class TransactionCommandRunner
{
    /** @var TransactionCommandInterface[] */
    private iterable $commands;

    public function __construct(iterable $commands)
    {
        $this->commands = $commands;
    }

    public function execute(Transaction $transaction): Transaction
    {
        /** @var TransactionCommandInterface $command */
        foreach ($this->commands as $command) {
            $transaction = $command->execute($transaction);
        }

        return $transaction;
    }
}
