<?php

namespace App\Form\DataTransformer;

use App\Repository\RoleRepository;
use Symfony\Component\Form\DataTransformerInterface;

class CodeArrayToRoleArrayTransformer implements DataTransformerInterface
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function reverseTransform($roleArray)
    {
        $codeArray = [];

        foreach ($roleArray as $role) {
            $codeArray[] = $role->getCode();
        }

        return $codeArray;
    }

    public function transform($codeArray)
    {
        $roleArray = [];

        foreach ($codeArray as $code) {
            $roleArray[] = $this->roleRepository->findOneBy(['code' => $code]);
        }

        return $roleArray;
    }
}
