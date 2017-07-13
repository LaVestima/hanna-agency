<?php

namespace LaVestima\HannaAgency\ProductBundle\Form;

use LaVestima\HannaAgency\ProducerBundle\Controller\Crud\ProducerCrudController;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\CategoryCrudController;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\SizeCrudController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

    public function __construct(
        CategoryCrudController $categoryCrudController,
        SizeCrudController $sizeCrudController,
        ProducerCrudController $producerCrudController
    ) {
        $this->categoryCrudController = $categoryCrudController;
        $this->sizeCrudController = $sizeCrudController;
        $this->producerCrudController = $producerCrudController;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: maybe only undeleted x2
        $categories = $this->categoryCrudController->readAllEntities()->getEntities();
        $sizes = $this->sizeCrudController->readAllEntities()->getEntities();
        $producers = $this->producerCrudController->readAllUndeletedEntities()->getEntities();

        $builder
            ->add('name', TextType::class)
            ->add('idCategories', ChoiceType::class, [
                'label' => 'Category',
                'choices' => $categories,
                'choice_label' => 'name',
                'placeholder' => 'Choose a category'
            ])
            ->add('idSizes', ChoiceType::class, [
                'label' => 'Size',
                'choices' => $sizes,
                'choice_label' => 'name',
                'placeholder' => 'Choose a size',
                'mapped' => false
            ])
            ->add('availability', NumberType::class, [
                'label' => 'Availability',
                'empty_data' => '0',
                'mapped' => false,
                'required' => false
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('isAdmin', null)
            ->setRequired('isAdmin')
            ->setAllowedTypes('isAdmin', ['boolean']);
    }
}