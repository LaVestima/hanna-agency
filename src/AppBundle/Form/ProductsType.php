<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 02.02.17
 * Time: 22:14
 */

namespace AppBundle\Form;


use AppBundle\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('name')
			->add('isSelected', CheckboxType::class, array(
				'mapped' => false,
				'required' => false,
			))
			->add('quantity', IntegerType::class, array(
				'mapped' => false,
				'label' => '',
				'data' => '0'
			))
		;
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => Products::class,
		));
	}
}