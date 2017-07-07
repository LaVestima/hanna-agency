<?php

namespace LaVestima\HannaAgency\CustomerBundle\Form;

use LaVestima\HannaAgency\LocationBundle\Controller\Crud\CityCrudController;
use LaVestima\HannaAgency\LocationBundle\Controller\Crud\CountryCrudController;
use LaVestima\HannaAgency\MoneyBundle\Controller\Crud\CurrencyCrudController;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NewCustomerType extends AbstractType
{
    private $countryCrudController;
    private $cityCrudController;
    private $currencyCrudController;
    private $userCrudController;

    public function __construct(
        CountryCrudController $countryCrudController,
        CityCrudController $cityCrudController,
        CurrencyCrudController $currencyCrudController,
        UserCrudController $userCrudController
    ) {
        $this->countryCrudController = $countryCrudController;
        $this->cityCrudController = $cityCrudController;
        $this->currencyCrudController = $currencyCrudController;
        $this->userCrudController = $userCrudController;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $countries = $this->countryCrudController->readAllEntities()->getEntities();
        $cities = $this->cityCrudController->readAllEntities()->getEntities();
        $currencies = $this->currencyCrudController->readAllEntities()->getEntities();
        $users = $this->userCrudController->readAllEntities()->getEntities();

        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 'M',
                    'Female' => 'F',
                    'Other' => 'O',
                ],
                'placeholder' => 'Choose a gender'
            ])
            ->add('companyName', TextType::class, [
                'required' => false
            ])
            ->add('vat', TextType::class, [
                'required' => false
            ])
            ->add('idCountries', ChoiceType::class, [
                'label' => 'Country',
                'choices' => $countries,
                'choice_label' => 'name',
                'placeholder' => 'Choose a country'
            ])
            ->add('idCities', ChoiceType::class, [
                'label' => 'City',
                'choices' => $cities,
                'choice_label' => 'name',
                'placeholder' => 'Choose a city'
            ])
            ->add('postalCode', TextType::class)
            ->add('street', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TextType::class)
            ->add('idCurrencies', ChoiceType::class, [
                'label' => 'Currency',
                'choices' => $currencies,
                'choice_label' => 'name',
                'placeholder' => 'Choose a currency'
            ])
            ->add('idUsers', ChoiceType::class, [
                'label' => 'User',
                'choices' => $users,
                'choice_label' => 'login',
                'placeholder' => 'Choose a user',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add Customer'
            ])
        ;
    }
}