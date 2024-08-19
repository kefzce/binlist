<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Chain\TransactionCommandInvoker;
use App\Transaction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProcessTransactionCommand extends Command
{
    public static string $defaultName = 'binlist';

    public function __construct(
        private TransactionCommandInvoker $commandRunner,
        private SerializerInterface $serializer
    ) {
        parent::__construct(self::$defaultName);
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->hasArgument('filepath') ? $input->getArgument('filePath') : null;
        $transactions = $this->processFile($filePath);

        foreach ($transactions as $transaction) {
            dump($this->commandRunner->execute($transaction));
        }

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addOption('filepath', null, InputOption::VALUE_OPTIONAL);
    }

    /**
     * @return Transaction[]
     */
    private function processFile(?string $filePath = null): array
    {
        $path = $filePath ?? __DIR__.'/../../input.txt';

        return $this->serializer->deserialize(file_get_contents($path), 'App\Transaction[]', 'json');
    }
}
