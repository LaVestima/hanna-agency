<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('frontPageHtml', CKEditorType::class, [
                'required' => false,
            ])
            ->add('logo', FileType::class, [
                'attr' => [
                    'accept' => 'image/*',
                ],
                'mapped' => false
            ])
            ->add('logoFilePath', HiddenType::class)
            ->add('submit', SubmitType::class)
        ;
    }
}
