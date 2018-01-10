<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Form;

use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\RoleCrudControllerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private $roleCrudController;

    public function __construct(
        RoleCrudControllerInterface $roleCrudController
    ) {
        $this->roleCrudController = $roleCrudController;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = $this->roleCrudController
            ->readAllEntities()
            ->getResult();

        $builder
            ->add('login', TextType::class)
            ->add('email', EmailType::class)
            ->add('idRoles', ChoiceType::class, [
                'label' => 'Role',
                'choices' => $roles,
                'choice_label' => 'name',
                'placeholder' => 'Choose a role',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('isNew', false)
            ->setAllowedTypes('isNew', ['bool']);
    }
}
