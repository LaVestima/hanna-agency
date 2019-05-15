<?php

namespace App\Form;

use App\Entity\ProductVariant;
use App\Repository\VariantRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariantType extends AbstractType
{
    private $variantRepository;

    /**
     * ProductType constructor.
     *
     * @param VariantRepository $variantRepository
     */
    public function __construct(
        VariantRepository $variantRepository
    ) {
        $this->variantRepository = $variantRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $variants = $this->variantRepository->readAllEntities()->getResult();

        $builder
            ->add('name', ChoiceType::class, [
                'choices' => $variants,
                'choice_label' => 'name',
                'placeholder' => 'Choose a variant',
                'property_path' => 'variant',
            ])
            ->add('availability', NumberType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductVariant::class
        ]);
    }
}
