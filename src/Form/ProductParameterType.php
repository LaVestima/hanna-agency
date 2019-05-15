<?php

namespace App\Form;

use App\Entity\ProductParameter;
use App\Repository\ParameterRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductParameterType extends AbstractType
{
    private $parameterRepository;

    public function __construct(
        ParameterRepository $parameterRepository
    ) {
        $this->parameterRepository = $parameterRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $parameters = $this->parameterRepository->readAllEntities()
            ->getResult();

        $builder
            ->add('name', ChoiceType::class, [
                'choices' => $parameters,
                'choice_label' => 'name',
                'placeholder' => 'Choose a parameter',
                'property_path' => 'parameter',
            ])
            ->add('value', TextType::class)
//            ->add('unit', null, [
//                'property_path' => 'parameter'
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductParameter::class
        ]);
    }
}
