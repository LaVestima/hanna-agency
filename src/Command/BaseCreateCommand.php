<?php

namespace App\Command;

use Faker\Factory;
use Symfony\Component\Console\Command\Command;

class BaseCreateCommand extends Command
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
