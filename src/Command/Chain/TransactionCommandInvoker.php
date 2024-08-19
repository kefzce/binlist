<?php

declare(strict_types=1);

namespace App\Command\Chain;

use App\Transaction;

class TransactionCommandInvoker
{
    /** @var TransactionCommandChainInterface[] */
    private iterable $commands;

    public function __construct(iterable $commands)
    {
        $this->commands = $commands;
    }

    public function execute(Transaction $transaction): Transaction
    {
        /** @var TransactionCommandChainInterface $command */
        foreach ($this->commands as $command) {
            $transaction = $command->handle($transaction);
        }

        return $transaction;
    }
}
