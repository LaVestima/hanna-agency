<?php

namespace App\Form\DataTransformer;

use App\Repository\CategoryRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdentifierToCategoryTransformer implements DataTransformerInterface
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function reverseTransform($category)
    {
        if (null === $category) {
            return '';
        }

        return $category->getIdentifier();
    }

    public function transform($categoryIdentifier)
    {
        if (!$categoryIdentifier) {
            return;
        }

        $category = $this->categoryRepository
            ->findOneBy([
                'identifier' => $categoryIdentifier
            ]);

        if (null === $category) {
            throw new TransformationFailedException();
        }

        return $category;
    }
}
