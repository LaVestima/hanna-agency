<?php

namespace LaVestima\HannaAgency\ProductBundle\Form;

use LaVestima\HannaAgency\ProducerBundle\Controller\Crud\ProducerCrudController;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\CategoryCrudControllerInterface;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\SizeCrudControllerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    private $categoryCrudController;
    private $sizeCrudController;
    private $producerCrudController;

    /**
     * ProductType constructor.
     *
     * @param CategoryCrudControllerInterface $categoryCrudController
     * @param SizeCrudControllerInterface $sizeCrudController
     * @param ProducerCrudController $producerCrudController
     */
    public function __construct(
        CategoryCrudControllerInterface $categoryCrudController,
        SizeCrudControllerInterface $sizeCrudController,
        ProducerCrudController $producerCrudController
    ) {
        $this->categoryCrudController = $categoryCrudController;
        $this->sizeCrudController = $sizeCrudController;
        $this->producerCrudController = $producerCrudController;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: maybe only undeleted x2
        $categories = $this->categoryCrudController->readAllEntities()->getResult();
        $sizes = $this->sizeCrudController->readAllEntities()->getResult();
        $producers = $this->producerCrudController->readAllUndeletedEntities()->getResult();

        $builder
            ->add('name', TextType::class)
            ->add('idCategories', ChoiceType::class, [
                'label' => 'Category',
                'choices' => $categories,
                'choice_label' => 'name',
                'placeholder' => 'Choose a category'
            ])
            ->add('sizes', CollectionType::class, [
                'label' => 'Size',
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'choices' => $sizes,
                    'choice_label' => 'name',
                    'placeholder' => 'Choose a size',
                ],
                'allow_add' => true,
                'prototype' => true,
                'prototype_data' => '',
                'mapped' => false,
            ])
            ->add('availabilities', CollectionType::class, [
                'label' => 'Availability',
                'entry_type' => NumberType::class,
                'entry_options' => [
                    'data' => 0,
                    'empty_data' => 0,
                ],
                'allow_add' => true,
                'prototype' => true,
                'prototype_data' => 0,
                'mapped' => false,
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
                'choices' => $producers,
                'choice_label' => 'fullName',
                'placeholder' => 'Choose a producer'
            ])
            // TODO: finish, add more !!!!!!!!!!!!
            ->add('save', SubmitType::class, [
                'label' => 'Add Product'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('isAdmin', null)
            ->setRequired('isAdmin')
            ->setAllowedTypes('isAdmin', ['boolean']);
    }
}