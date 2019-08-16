<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\ShipmentOption;
use App\Entity\User;
use App\Repository\ShipmentOptionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartSummaryType extends AbstractType
{
    private $shipmentOptionRepository;

    public function __construct(ShipmentOptionRepository $shipmentOptionRepository)
    {
        $this->shipmentOptionRepository = $shipmentOptionRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('products', ChoiceType::class, [

            ])
            ->add('shipmentOption', ChoiceType::class, [
                'choices' => $this->shipmentOptionRepository->findAll(),
                'choice_label' => function (ShipmentOption $choice) {
                    return $choice->getName() . ' (€ ' . $choice->getCost() . ')';
                },
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('address', ChoiceType::class, [
                'choices' => $options['user']->getAddresses(),
                'choice_label' => function (Address $choice, $key, $value) {
//                    return '<div><h3>' . $choice->getName() . '</h3></div>';
                    return $choice->getName() . $choice->getCountry()->getName();
//                    return $choice->getName() . $key . $value;

                    // or if you want to translate some key
                    //return 'form.choice.'.$key;
                },
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('privacyPolicy', CheckboxType::class, [
                'label' => 'I accept anything and everything!',
                'required' => true,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('user', null)
            ->setRequired('user')
            ->setAllowedTypes('user', [User::class, 'null']);
    }
}