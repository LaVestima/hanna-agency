<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\CartProductVariant;
use App\Entity\ShipmentOption;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\ShipmentOptionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CartSummaryType extends AbstractType
{
    private $cartRepository;
    private $shipmentOptionRepository;
    private $request;

    public function __construct(
        CartRepository $cartRepository,
        ShipmentOptionRepository $shipmentOptionRepository,
        RequestStack $requestStack
    ) {
        $this->cartRepository = $cartRepository;
        $this->shipmentOptionRepository = $shipmentOptionRepository;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['paymentChargeRoute'])
            ->add('products', ChoiceType::class, [
                'choices' => $this->cartRepository->findOneBy([
                    'sessionId' => $this->request->getSession()->getId()
                ])->getCartProductVariants(),
                'choice_label' => function (CartProductVariant $cartProductVariant) {
                    return $cartProductVariant->getProductVariant()->getProduct()->getName() .
                        ' (' . $cartProductVariant->getProductVariant()->getVariant()->getName() . ') x' .
                        $cartProductVariant->getQuantity();
                },
                'group_by' => function (CartProductVariant $cartProductVariant) {
                    return $cartProductVariant->getProductVariant()->getProduct()->getStore()->getShortName();
                },
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('shipmentOption', ChoiceType::class, [
                'choices' => $this->shipmentOptionRepository->findAll(),
                'choice_label' => function (ShipmentOption $choice) {
                    return $choice->getName() . ' (€ ' . $choice->getCost() . ')';
                },
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('address', ChoiceType::class, [
                'choices' => $options['user']->getAddresses(),
                'choice_label' => function (Address $choice, $key, $value) {
//                    return '<div><h3>' . $choice->getName() . '</h3></div>';
                    return $choice->getName() . $choice->getCountry()->getName();
//                    return $choice->getName() . $key . $value;
                },
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('privacyPolicy', CheckboxType::class, [
                'label' => 'I accept anything and everything! (privacy policy)',
                'required' => true,
            ])
            ->add('token', HiddenType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('user', null)
            ->setRequired('user')
            ->setAllowedTypes('user', [User::class, 'null'])
            ->setDefault('paymentChargeRoute', '')
            ->setAllowedTypes('paymentChargeRoute', ['string'])
        ;
    }

    public function getBlockPrefix()
    {
        return 'payment-form';
    }
}
