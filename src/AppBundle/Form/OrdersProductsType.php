<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 02.02.17
 * Time: 20:20
 */

namespace AppBundle\Form;


use AppBundle\Entity\OrdersProducts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersProductsType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('idProducts', CollectionType::class, array(
				'entry_type' => ProductsType::class,
			))
			->add('submit', SubmitType::class, array(
				'label' => 'Submit',
			))
		;
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => OrdersProducts::class,
		));
	}
}