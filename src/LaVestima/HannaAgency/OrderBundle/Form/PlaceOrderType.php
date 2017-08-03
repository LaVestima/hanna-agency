<?php

namespace LaVestima\HannaAgency\OrderBundle\Form;

use LaVestima\HannaAgency\CustomerBundle\Controller\Crud\CustomerCrudControllerInterface;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\ProductCrudControllerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceOrderType extends AbstractType
{
    private $productCrudController;
    private $customerCrudController;

    /**
     * PlaceOrderType constructor.
     *
     * @param ProductCrudControllerInterface $productCrudController
     * @param CustomerCrudControllerInterface $customerCrudController
     */
    public function __construct(
        ProductCrudControllerInterface $productCrudController,
        CustomerCrudControllerInterface $customerCrudController
    ) {
        $this->productCrudController = $productCrudController;
        $this->customerCrudController = $customerCrudController;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $products = $this->productCrudController
            ->readAllUndeletedEntities()
            ->getResult();

        $builder
            ->add('products', ChoiceType::class, [
                'choices' => $products,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('quantities', CollectionType::class)
            ->add('save', SubmitType::class, array('label' => 'Place order'));
        if ($options['isAdmin']) {
            $customers = $this->customerCrudController
                ->readAllUndeletedEntities()
                ->getResult();

            $builder
                ->add('customers', ChoiceType::class, [
                    'label' => 'Customer',
                    'choices' => $customers,
                    'choice_label' => 'fullName'
                ]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('isAdmin', null)
            ->setRequired('isAdmin')
            ->setAllowedTypes('isAdmin', ['bool']);
    }
}