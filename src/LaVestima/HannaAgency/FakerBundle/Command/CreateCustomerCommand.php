<?php

namespace LaVestima\HannaAgency\FakerBundle\Command;

use LaVestima\HannaAgency\CustomerBundle\Entity\Customers;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCustomerCommand extends BaseCreateCommand
{
    public function configure()
    {
        $this
            ->setName('faker:create:customer')
            ->setDescription('Creates fake customers')
            ->addArgument('number', InputArgument::OPTIONAL, 'Number of customers to create', 1);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $productNumber = (int)$input->getArgument('number') ?? 1;

        if ($productNumber < 1) {
            $output->writeln('Wrong argument!');
        } else {
            for ($i = 0; $i < $productNumber; $i++) {
                $this->createFakeCustomer();

                $output->writeln('Customer');
            }

            $output->writeln('Created: ' . $i);
        }
    }

    private function createFakeCustomer()
    {
        $customer = new Customers();

        $randomCountry = $this->getContainer()->get('country_crud_controller')
            ->readRandomEntities(1)->getResult();

        $randomCity = $this->getContainer()->get('city_crud_controller')
            ->readRandomEntities(1)->getResult();

        $randomCurrency = $this->getContainer()->get('currency_crud_controller')
            ->readRandomEntities(1)->getResult();

        // TODO: check ident. uniquness
        $customer->setIdentificationNumber($this->faker->numberBetween(1000000000, 9999999999));
        $customer->setFirstName($this->faker->firstName);
        $customer->setLastName($this->faker->lastName);
        $customer->setGender($this->faker->regexify('[MFO]'));
        $customer->setCompanyName($this->faker->company);
        $customer->setVat($this->faker->regexify('[A-Z]{2}[0-9]{8}'));
        $customer->setEmail($this->faker->safeEmail);
//        $customer->setNewsletter($this->faker->numberBetween(0, 1));
        $customer->setPhone($this->faker->phoneNumber);
        $customer->setDefaultDiscount($this->faker->numberBetween(0, 90));
        $customer->setIdCountries($randomCountry);
        $customer->setIdCities($randomCity); // TODO: only suitable to country
        $customer->setPostalCode($this->faker->postcode);
        $customer->setStreet($this->faker->streetAddress);
        $customer->setIdCurrencies($randomCurrency);
        // TODO: add randomly
        $customer->setIdUsers();

        $this->getContainer()->get('customer_crud_controller')
            ->createEntity($customer);

        return $customer;
    }
}
