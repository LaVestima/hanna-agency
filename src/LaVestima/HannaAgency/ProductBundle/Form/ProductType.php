<?php

namespace LaVestima\HannaAgency\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('idCategories', ChoiceType::class, [
                'label' => 'Category',
                'choices' => $options['categories'],
                'choice_label' => 'name',
                'placeholder' => 'Choose a category'
            ])
            ->add('idSizes', ChoiceType::class, [
                'label' => 'Size',
                'choices' => $options['sizes'],
                'choice_label' => 'name',
                'placeholder' => 'Choose a size'
            ])
            ->add('priceProducer', MoneyType::class, [
                'label' => 'Producer Price',
                'currency' => false
            ])
            ->add('priceCustomer', MoneyType::class, [
                'label' => 'Customer Price',
                'currency' => false
            ])
            ->add('idProducers', ChoiceType::class, [
                'label' => 'Producer',
                'choices' => $options['producers'],
                'choice_label' => 'fullName',
                'placeholder' => 'Choose a producer'
            ])
            // TODO: finish, add more !!!!!!!!!!!!
            ->add('save', SubmitType::class, [
                'label' => 'Add Product'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('categories', null)
            ->setRequired('categories')
            ->setAllowedTypes('categories', array('array'));
        $resolver
            ->setDefault('sizes', null)
            ->setRequired('sizes')
            ->setAllowedTypes('sizes', array('array'));
        $resolver
            ->setDefault('producers', null)
            ->setRequired('producers')
            ->setAllowedTypes('producers', array('array'));
    }
}