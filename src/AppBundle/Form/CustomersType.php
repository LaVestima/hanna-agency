<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CustomersType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('firstName', TextType::class)
			->add('lastName', TextType::class)
			->add('gender', ChoiceType::class, array(
				'choices' => array(
					'Female' => 'F',
					'Male' => 'M',
					'Other' => 'O',
				),
				'placeholder' => '',
				'required' => true,
			))
			->add('companyName', TextType::class, array(
				'required' => false,
			))
			->add('vat', TextType::class, array(
				'required' => false,
			))
//			->add('idCountries', null, array(
//				'label' => 'Country',
//				'placeholder' => '',
//				'required' => true
//			))
			->add('idCountries', EntityType::class, array(
				'class' => 'AppBundle:Countries',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('co')
						->orderBy('co.name', 'ASC');
				},
				'label' => 'Country',
				'placeholder' => '',
				'required' => true
			))
			->add('idCities', null, array(
				'label' => 'City',
				'placeholder' => '',
				'required' => true
			))
			->add('street', TextType::class)
			->add('postalCode', TextType::class)
			->add('email', EmailType::class)
			->add('phone', TextType::class)
			->add('idCurrencies', null, array(
				'label' => 'Currency',
				'placeholder' => '',
				'required' => true
			))
			->add('defaultDiscount', IntegerType::class, array(
				'required' => false,
			))
			->add('idUsers', null, array(
				'placeholder' => '',
				'required' => false
			))
			->add('add', SubmitType::class, array(
				'label' => 'Add',
			))
		;
	}
}