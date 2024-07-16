<?php

declare(strict_types=1);

namespace App\Command;

use App\Transaction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProcessTransactionChainCommand extends Command
{
    public static string $defaultName = 'binlist';

    public function __construct(
        private TransactionCommandRunner $commandRunner,
        private SerializerInterface $serializer
    ) {
        parent::__construct(self::$defaultName);
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $transactions = $this->processFile();

        foreach ($transactions as $transaction) {
            dump($this->commandRunner->execute($transaction));
        }

        return Command::SUCCESS;
    }

    /**
     * @return Transaction[]
     */
    private function processFile(): array
    {
        return $this->serializer->deserialize(file_get_contents(__DIR__.'/../../'.'input.txt'), 'App\Transaction[]', 'json');
    }
}
