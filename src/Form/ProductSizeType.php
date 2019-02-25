<?php

namespace App\Form;

use App\Entity\ProductSize;
use App\Repository\SizeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSizeType extends AbstractType
{
    private $sizeRepository;

    /**
     * ProductType constructor.
     *
     * @param SizeRepository $sizeRepository
     */
    public function __construct(
        SizeRepository $sizeRepository
    ) {
        $this->sizeRepository = $sizeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sizes = $this->sizeRepository->readAllEntities()->getResult();

        $builder
            ->add('name', ChoiceType::class, [
                'choices' => $sizes,
                'choice_label' => 'name',
                'placeholder' => 'Choose a size',
                'property_path' => 'idSizes',
            ])
            ->add('availability', NumberType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductSize::class
        ]);
    }
}
