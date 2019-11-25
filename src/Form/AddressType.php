<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Country;
use App\Repository\CountryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class AddressType extends AbstractType
{
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Name'
                ],
                'required' => false
            ])
            ->add('country2', Select2EntityType::class, [
                'class' => Country::class,
                'remote_route' => 'homepage_homepage',
                'mapped' => false
            ])
            ->add('country', ChoiceType::class, [
                'choices' => $this->countryRepository->findAll(),
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Choose a country',
                'attr' => [
                    'class' => 'select2'
                ],
            ])
//            ->add('country', CountryType::class)
            ->add('street', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Street'
                ],
            ])
            ->add('zipCode', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Zip code'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class
        ]);
    }
}
