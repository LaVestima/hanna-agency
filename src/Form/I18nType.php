<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\FormBuilderInterface;

class I18nType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', LanguageType::class, [
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('currency', CurrencyType::class, [
                'attr' => [
                    'class' => 'select2'
                ]
            ])
        ;
    }
}
