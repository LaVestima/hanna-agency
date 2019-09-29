<?php

namespace App\Form;

use App\Entity\ProductPromotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('discountedPrice', MoneyType::class)
            ->add('startDate', DateTimeType::class, [
                'label' => 'Start'
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'End'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductPromotion::class
        ]);
    }
}
