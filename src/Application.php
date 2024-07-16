<?php

declare(strict_types=1);

namespace App;

use App\Command\ProcessTransactionChainCommand;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct(iterable $commands = [])
    {
        parent::__construct('binlist', '1.0.0');
        foreach ($commands as $command) {
            $this->add($command);
        }
        $this->setDefaultCommand(ProcessTransactionChainCommand::$defaultName, true);
    }
}
