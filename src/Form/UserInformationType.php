<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\Genders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'First Name'
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Last Name'
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => Genders::getConstants(),
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
                }
            ])

            ->add('submit', SubmitType::class)
        ;
    }
}
