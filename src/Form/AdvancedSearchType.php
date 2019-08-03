<?php

namespace App\Form;

use App\Form\DataTransformer\IdentifierToCategoryTransformer;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdvancedSearchType extends AbstractType
{
    private $categoryToIdentifierTransformer;
    private $categoryRepository;

    public function __construct(
        IdentifierToCategoryTransformer $categoryToIdentifierTransformer,
        CategoryRepository $categoryRepository
    ) {
        $this->categoryToIdentifierTransformer = $categoryToIdentifierTransformer;
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $this->categoryRepository->findAll();

        $builder
            ->setMethod('GET')
            ->add('query', TextType::class, [
                'label' => 'Search query',
                'required' => false,
            ])
            ->add('category', ChoiceType::class, [
                'choices' => $categories,
                'choice_label' => 'name',
                'choice_value' => 'identifier',
                'required' => false,
                'placeholder' => 'Choose an option',
            ])
            ->add('priceMin', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 99999,
                    'step' => 0.1
                ],
                'data' => 0,
                'empty_data' => 0,
            ])
            ->add('priceMax', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 99999,
                    'step' => 0.1
                ],
                'data' => 99999, // TODO: change to the highest value in products
                'empty_data' => 99999,
            ])
            ->add('country', CountryType::class)
            ->add('sorting', ChoiceType::class, [
                'label' => 'Sort by',
                'choices' => [
                    'Most relevant' => 'mostRelevant',
                    'Price ascending' => 'priceAsc',
                    'Price descending' => 'priceDesc',
                    'Reviews descending' => 'reviewDesc'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filter'
            ])
        ;

        $builder->get('category')
            ->addModelTransformer($this->categoryToIdentifierTransformer);
    }

    public function getBlockPrefix()
    {
        return 's_a';
    }
}
