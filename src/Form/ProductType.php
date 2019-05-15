<?php

namespace App\Form;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProducerRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    private $categoryRepository;
    private $producerRepository;

    /**
     * ProductType constructor.
     *
     * @param CategoryRepository $categoryRepository
     * @param ProducerRepository $producerRepository
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        ProducerRepository $producerRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->producerRepository = $producerRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: maybe only undeleted
        $categories = $this->categoryRepository->readAllEntities()->getResultAsArray();
        $producers = $this->producerRepository->readAllEntities()->onlyUndeleted()->getResult();

        $builder
            ->add('name', TextType::class)
            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'choices' => $categories,
                'choice_label' => 'name',
                'choice_value' => function ($entity = null) {
                    return $entity ? $entity->getId() : '';
                },
                'placeholder' => 'Choose a category'
            ])
            ->add('variants', CollectionType::class, [
                'label' => 'Variants',
                'entry_type' => ProductVariantType::class,
                'property_path' => 'productVariants',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('parameters', CollectionType::class, [
                'label' => 'Parameters',
                'entry_type' => ProductParameterType::class,
                'property_path' => 'productParameters',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('images', CollectionType::class, [
                'label' => 'Images',
                'entry_type' => ProductImageType::class,
//                'entry_options' => [
//                    'data_class' => null,
//                    'attr' => [
//                        'accept' => 'image/*',
//                    ],
//                ],
                'property_path' => 'productImages',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'required' => false,
            ])
//            ->add('images', CollectionType::class, [
//                'label' => 'Images',
//                'entry_type' => FileType::class,
//                'entry_options' => [
//                    'data_class' => null,
//                    'attr' => [
//                        'accept' => 'image/*',
//                    ],
//                ],
//                'property_path' => 'productImages',
//                'allow_add' => true,
//                'allow_delete' => true,
//                'prototype' => true,
//                'by_reference' => false,
//                'required' => false,
//            ])
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
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);

        $resolver
            ->setDefault('edit', false)
            ->setRequired('isAdmin')
            ->setRequired('isProducer')
            ->setAllowedTypes('edit', ['boolean'])
            ->setAllowedTypes('isAdmin', ['boolean'])
            ->setAllowedTypes('isProducer', ['boolean']);
    }
}
