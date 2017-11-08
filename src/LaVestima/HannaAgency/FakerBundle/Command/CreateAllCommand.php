<?php

namespace LaVestima\HannaAgency\FakerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAllCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('faker:create:all')
            ->setDescription('Creates fake entities')
            ->addArgument('number', InputArgument::OPTIONAL, 'Number of entities to create');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $entityNumber = (int)$input->getArgument('number') ?: 1;

        $commandNames = [
            'faker:create:customer',
            'faker:create:product',
            'faker:create:order',
            'faker:create:user',
            // TODO: add more commands here
        ];

        foreach ($commandNames as $commandName) {
            $command = $this->getApplication()->find($commandName);
            $arguments = [
                'command' => $commandName,
                'number' => $entityNumber
            ];

            $commandInput = new ArrayInput($arguments);
            $command->run($commandInput, $output);
        }
    }
}