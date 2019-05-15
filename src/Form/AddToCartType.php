<?php

namespace App\Form;

use App\Repository\VariantRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddToCartType extends AbstractType
{
    public $variantRepository;

    public function __construct(VariantRepository $variantRepository)
    {
        $this->variantRepository = $variantRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $product = $builder->getData();

        $builder
            ->add('productVariant', ChoiceType::class, [
                'choices' => $product->getProductVariants(),
                'choice_label' => 'variant.name',
                'choice_value' => 'identifier',
                'expanded' => true,
                'multiple' => false,
                'placeholder' => 'Choose variant',
                'mapped' => false,
                'required' => true,
            ])
            ->add('quantity', NumberType::class, [
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Add to cart',
//                'attr' => [
//                    'data-product' => $product->getPathSlug()
//                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'id' => 'add_to_cart_form'
            ]
        ]);
    }
}
