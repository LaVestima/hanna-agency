<?php

namespace LaVestima\HannaAgency\OrderBundle\Form;

use LaVestima\HannaAgency\CustomerBundle\Controller\Crud\CustomerCrudControllerInterface;
use LaVestima\HannaAgency\ProductBundle\Controller\Crud\ProductSizeCrudControllerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceOrderType extends AbstractType
{
    private $productSizeCrudController;
    private $customerCrudController;

    /**
     * PlaceOrderType constructor.
     *
     * @param ProductSizeCrudControllerInterface $productSizeCrudController
     * @param CustomerCrudControllerInterface $customerCrudController
     */
    public function __construct(
        ProductSizeCrudControllerInterface $productSizeCrudController,
        CustomerCrudControllerInterface $customerCrudController
    ) {
        $this->productSizeCrudController = $productSizeCrudController;
        $this->customerCrudController = $customerCrudController;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $productsSizes = $this->productSizeCrudController
            ->setAlias('ps')
            ->readAllEntities()
            ->join('idProducts', 'p')
            ->orderBy('p.name', 'ASC')
            ->getResult();

        $builder
            ->add('productsSizes', ChoiceType::class, [
                'choices' => $productsSizes,
                'choice_label' => 'idProducts.name',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('quantities', CollectionType::class)
            ->add('sizes', ChoiceType::class, [
                'choices' => $productsSizes,
                'choice_label' => 'idSizes.name',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('availabilities', ChoiceType::class, [
                'choices' => $productsSizes,
                'choice_label' => 'availability',
                'expanded' => true,
                'multiple' => true,
            ])
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