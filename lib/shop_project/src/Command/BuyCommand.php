<?php

// src/Command/BuyCommand.php
namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class BuyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('shop:buy')

            ->setDescription('Buys Products')

            ->setHelp('This command allows you to buy stuff...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $db = new \jsonConnector();

        $shop = new \Shop($db);
        $shop->run('Buy');
    }
}