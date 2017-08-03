<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locale', TextType::class) // TODO: change to ChoiceType when locale table is added
            ->add('newsletter', CheckboxType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Confirm',
            ])
        ;
    }
}