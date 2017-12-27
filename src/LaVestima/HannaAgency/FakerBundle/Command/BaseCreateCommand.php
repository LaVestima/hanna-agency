<?php

namespace LaVestima\HannaAgency\FakerBundle\Command;

use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class BaseCreateCommand extends ContainerAwareCommand
{
    protected $faker;

    /**
     * BaseCreateCommand constructor.
     *
     * @param null $name
     */
    public function __construct($name = null)
    {
        $this->faker = Factory::create();

        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('.'); // Useless
    }
}