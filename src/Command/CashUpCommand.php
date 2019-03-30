<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CashUpCommand extends Command
{
    protected static $defaultName = 'keshbek:cash-up';

    protected function configure()
    {
        $this
            ->setDescription('Creates a cash-up')
            ->setHelp('This command allows you to create a cash-up')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //do stuff
    }
}
