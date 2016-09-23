<?php

// src/Command/ProfitCommand.php
namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProfitCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('shop:profit')

            ->setDescription('Displays Profit')

            ->setHelp('This command allows you to view the total profit...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $shop = $this->getApplication()->getShop();

        $result = $shop->profit();

        $output->writeln($result);
    }
}