<?php

namespace LaVestima\HannaAgency\CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewCustomerType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 'M',
                    'Female' => 'F',
                    'Other' => 'O',
                ],
            ])
            ->add('idCountries', ChoiceType::class, [
                'choices' => $options['countries'],
                'choice_label' => 'name',
            ])
            ->add('idCities', ChoiceType::class, [
                'choices' => $options['cities'],
                'choice_label' => 'name',
            ])
            ->add('postalCode', TextType::class)
            ->add('street', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TextType::class)
            ->add('idCurrencies', ChoiceType::class, [
                'choices' => $options['currencies'],
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add Customer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver
            ->setDefault('countries', null)
            ->setRequired('countries')
            ->setAllowedTypes('countries', array('array'))
        ;
        $resolver
            ->setDefault('cities', null)
            ->setRequired('cities')
            ->setAllowedTypes('cities', array('array'))
        ;
        $resolver
            ->setDefault('currencies', null)
            ->setRequired('currencies')
            ->setAllowedTypes('currencies', array('array'))
        ;
    }
}