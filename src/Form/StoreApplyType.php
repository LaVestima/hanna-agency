<?php

namespace App\Form;

use App\Form\DataTransformer\CountryToCodeTransformer;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class StoreApplyType extends AbstractType
{
    private $countryToCodeTransformer;
    private $cityRepository;

    public function __construct(
        CountryToCodeTransformer $countryToCodeTransformer,
        CityRepository $cityRepository
    ) {
        $this->countryToCodeTransformer = $countryToCodeTransformer;
        $this->cityRepository = $cityRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vat', TextType::class, [
                'label' => 'VAT number'
            ])
            ->add('fullName', TextType::class)
            ->add('shortName', TextType::class)
            // TODO: ...
            ->add('phone', TextType::class)
            ->add('email', EmailType::class)
            ->add('country', CountryType::class, [
                'placeholder' => 'Choose an option',
            ])
            ->add('city', ChoiceType::class, [
                'choices' => $this->cityRepository->findAll(),
                'choice_label' => 'name'
            ])
            ->add('postalCode', TextType::class)
            ->add('street', TextType::class)
            ->add('submit', SubmitType::class)
        ;

        $builder->get('country')
            ->addModelTransformer($this->countryToCodeTransformer);
    }
}
