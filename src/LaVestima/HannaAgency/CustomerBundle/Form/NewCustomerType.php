<?php

namespace LaVestima\HannaAgency\CustomerBundle\Form;

use LaVestima\HannaAgency\LocationBundle\Controller\Crud\CityCrudControllerInterface;
use LaVestima\HannaAgency\LocationBundle\Controller\Crud\CountryCrudControllerInterface;
use LaVestima\HannaAgency\MoneyBundle\Controller\Crud\CurrencyCrudControllerInterface;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudControllerInterface;
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

    /**
     * NewCustomerType constructor.
     *
     * @param CountryCrudControllerInterface $countryCrudController
     * @param CityCrudControllerInterface $cityCrudController
     * @param CurrencyCrudControllerInterface $currencyCrudController
     * @param UserCrudControllerInterface $userCrudController
     */
    public function __construct(
        CountryCrudControllerInterface $countryCrudController,
        CityCrudControllerInterface $cityCrudController,
        CurrencyCrudControllerInterface $currencyCrudController,
        UserCrudControllerInterface $userCrudController
    ) {
        $this->countryCrudController = $countryCrudController;
        $this->cityCrudController = $cityCrudController;
        $this->currencyCrudController = $currencyCrudController;
        $this->userCrudController = $userCrudController;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $countries = $this->countryCrudController
            ->readAllEntities()
            ->orderBy('name', 'ASC')
            ->getResult();
        $cities = $this->cityCrudController
            ->readAllEntities()
            ->orderBy('name', 'ASC')
            ->getResult();
        $currencies = $this->currencyCrudController
            ->readAllEntities()
            ->orderBy('name', 'ASC')
            ->getResult();
        $users = $this->userCrudController->readAllEntities()
            ->orderBy('login', 'ASC')
            ->getResult();

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
            ]);
    }
}
