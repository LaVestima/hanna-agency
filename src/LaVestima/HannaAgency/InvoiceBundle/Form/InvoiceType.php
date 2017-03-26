<?php

namespace LaVestima\HannaAgency\InvoiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class InvoiceType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('name', TextType::class)
//			->add('login', TextType::class)
//			->add('email', EmailType::class)
//			->add('password', RepeatedType::class, [
//				'type' => PasswordType::class,
//				'mapped' => false,
//				'first_options'  => ['label' => 'Password'],
//				'second_options' => ['label' => 'Repeat Password'],
//			])
			->add('save', SubmitType::class, array('label' => 'Add invoice'))
		;
	}
}