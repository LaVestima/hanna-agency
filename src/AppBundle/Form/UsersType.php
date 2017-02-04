<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 27.01.17
 * Time: 13:15
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UsersType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
//			->add('email', EmailType::class)
			->add('login', TextType::class)
			->add('plainPassword', RepeatedType::class, array(
				'type' => PasswordType::class,
				'first_options'  => array('label' => 'Password'),
				'second_options' => array('label' => 'Repeat Password'),
			))
			->add('save', SubmitType::class, array('label' => 'Register'))
		;
	}
}