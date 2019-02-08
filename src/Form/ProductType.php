<?php

namespace App\Form;


use App\Repository\CategoryRepository;
use App\Repository\CompanyRepository;
use App\Repository\SizeRepository;
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
    private $categoryRepository;
    private $sizeRepository;
    private $producerRepository;

//    private $categoryCrudController;
//    private $sizeCrudController;
//    private $producerCrudController;

    /**
     * ProductType constructor.
     *
     * @param CategoryRepository $categoryRepository
     * @param SizeRepository $sizeRepository
     * @param CompanyRepository $producerRepository
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        SizeRepository $sizeRepository,
        CompanyRepository $producerRepository
//        CategoryCrudControllerInterface $categoryCrudController,
//        SizeCrudControllerInterface $sizeCrudController,
//        ProducerCrudController $producerCrudController
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->sizeRepository = $sizeRepository;
        $this->producerRepository = $producerRepository;
//        $this->categoryCrudController = $categoryCrudController;
//        $this->sizeCrudController = $sizeCrudController;
//        $this->producerCrudController = $producerCrudController;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: maybe only undeleted x2
        $categories = $this->categoryRepository->readAllEntities()->getResult();
        $sizes = $this->sizeRepository->readAllEntities()->getResult();
        $producers = $this->producerRepository->readAllEntities()->onlyUndeleted()->getResult();

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
                'label' => 'Company Price',
                'currency' => false
            ])
            ->add('priceCustomer', MoneyType::class, [
                'label' => 'Customer Price',
                'currency' => false
            ])
            ->add('idProducers', ChoiceType::class, [
                'label' => 'Company',
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