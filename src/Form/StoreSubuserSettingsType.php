<?php

namespace App\Form;

use App\Form\DataTransformer\CodeArrayToRoleArrayTransformer;
use App\Repository\RoleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class StoreSubuserSettingsType extends AbstractType
{
    private $codeArrayToRoleArrayTransformer;
    private $roleRepository;

    public function __construct(
        CodeArrayToRoleArrayTransformer $codeArrayToRoleArrayTransformer,
        RoleRepository $roleRepository
    ) {
        $this->codeArrayToRoleArrayTransformer = $codeArrayToRoleArrayTransformer;
        $this->roleRepository = $roleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => $this->roleRepository->findBy(['subrole' => true]),
                'choice_label' => 'name', // TODO: change???
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('submit', SubmitType::class)
        ;

        $builder->get('roles')
            ->addModelTransformer($this->codeArrayToRoleArrayTransformer);
    }
}
