<?php

namespace LaVestima\HannaAgency\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceOrderType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('products', ChoiceType::class, [
                'choices' => $options['products'],
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('save', SubmitType::class, array('label' => 'Place order'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver
            ->setDefault('products', null)
            ->setRequired('products')
            ->setAllowedTypes('products', array('array'))
        ;
    }
}