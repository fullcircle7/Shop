<?php

// src/Command/SellCommand.php
namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SellCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('shop:sell')

            ->setDescription('Sells Products')

            ->setHelp('This command allows you to sell stuff...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $shop = $this->getApplication()->getShop();

        $result = $shop->sell();

        $output->writeln($result);
    }
}