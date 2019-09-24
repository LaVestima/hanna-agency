<?php

namespace App\Form;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    private $categoryRepository;

    /**
     * ProductType constructor.
     *
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $product = $builder->getData();

        // TODO: maybe only undeleted
        $categories = $this->categoryRepository->readAllEntities()->getResultAsArray();

        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Customer Price',
                'data' => $product->getProductPromotions()->isEmpty()
                    ? $product->getPrice()
                    : $product->getOldPrice(),
                'currency' => false
            ])
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
                'property_path' => 'productImages',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'required' => false,
            ])
            ->add('promotions', CollectionType::class, [
                'label' => 'Promotions',
                'entry_type' => ProductPromotionType::class,
                'property_path' => 'productPromotions',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'required' => false,
            ])
        ;

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
