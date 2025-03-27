<?php

namespace App\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'hello-world')]
class HelloWorld extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Say hello!')
            ->addArgument('name', InputArgument::REQUIRED, 'What is your name?');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $output->writeln('Hello ' . $name . '!');
        return Command::SUCCESS;
    }
}
