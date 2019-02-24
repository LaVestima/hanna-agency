<?php

namespace App\Form;

use App\Repository\CategoryRepository;
use App\Repository\ProducerRepository;
use App\Repository\SizeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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

    /**
     * ProductType constructor.
     *
     * @param CategoryRepository $categoryRepository
     * @param SizeRepository $sizeRepository
     * @param ProducerRepository $producerRepository
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        SizeRepository $sizeRepository,
        ProducerRepository $producerRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->sizeRepository = $sizeRepository;
        $this->producerRepository = $producerRepository;
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
                'choice_value' => function ($entity = null) {
                    return $entity ? $entity->getId() : '';
                },
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
            ]);

        if ($options['isAdmin'] && !$options['isProducer']) {
            $builder
                ->add('idProducers', ChoiceType::class, [
                    'label' => 'Producer',
                    'choices' => $producers,
                    'choice_label' => 'fullName',
                    'placeholder' => 'Choose a producer'
                ]);
        }

        if ($options['edit']) {
            $builder
                ->add('active', CheckboxType::class, [
                    'required' => false,
                ]);
        }
//            ->add('images', FileType::class, [
//                'label' => 'Images',
////                'multiple' => true,
//                'attr'     => [
//                    'accept' => 'image/*',
////                    'multiple' => 'multiple'
//                ],
//                'mapped' => false, // TODO: other way?
//            ])
            // TODO: finish, add more !!!!!!!!!!!!
        $builder
            ->add('save', SubmitType::class, [
                'label' => $options['edit'] ? 'Save' : 'Add Product'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('edit', false)
            ->setRequired('isAdmin')
            ->setRequired('isProducer')
            ->setAllowedTypes('edit', ['boolean'])
            ->setAllowedTypes('isAdmin', ['boolean'])
            ->setAllowedTypes('isProducer', ['boolean']);
    }
}
