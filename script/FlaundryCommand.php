<?php 
    

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FlaundryCommand extends Command
{
    protected static $defaultName = 'flaundry';

    protected function configure()
    {
        $this
            ->setDescription('Toggle maintenance mode')
            ->setHelp('This command allows you to toggle maintenance mode...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Your command logic goes here
        $output->writeln('Maintenance mode toggled');

        // Return the exit status
        return Command::SUCCESS;
    }
}