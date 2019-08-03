<?php

namespace App\Form;

use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchBarType extends AbstractType
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $this->categoryRepository->findBy(['parent' => null]);

        $builder
            ->setMethod('GET')
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Search...',
                    'autocomplete' => 'off'
                ],
            ])
            ->add('category', ChoiceType::class, [
                'choices' => $categories,
                'choice_label' => 'name',
                'choice_value' => 'identifier',
                'required' => false,
                'placeholder' => 'All categories',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
