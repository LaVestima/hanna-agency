<?php

namespace LaVestima\HannaAgency\FakerBundle\Command;

use Faker\Factory;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends ContainerAwareCommand
{
    private $faker;

    public function __construct($name = null)
    {
        $this->faker = Factory::create();

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('faker:create:user')
            ->setDescription('Creates fake users')
            ->addOption('number', null, InputOption::VALUE_OPTIONAL, 'Number of users to create');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userNumber = (int)$input->getOption('number') ?: 1;

        if ($userNumber < 1) {
            $output->writeln('Wrong argument!');
        } else {
            for ($i = 0; $i < $userNumber; $i++) {
                $this->createFakeUser();

                $output->writeln('User');
            }
        }

        $output->writeln($userNumber . ' user' . ($userNumber == 1 ? '' : 's') . ' created!');
    }

    private function createFakeUser()
    {
        $user = new Users();

        $randomRole = $this->getContainer()->get('role_crud_controller')
            ->readRandomEntities(1);

        $user->setLogin($this->faker->userName);
        $user->setEmail($this->faker->safeEmail);
        $user->setPasswordHash(password_hash($this->faker->password, PASSWORD_BCRYPT));
        $user->setIdRoles($randomRole);

        $this->getContainer()->get('user_crud_controller')
            ->createEntity($user);
    }
}