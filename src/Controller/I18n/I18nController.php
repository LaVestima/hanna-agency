<?php

namespace App\Controller\I18n;

use App\Controller\Infrastructure\BaseController;
use App\Form\I18nType;

class I18nController extends BaseController
{
    public function dropdown()
    {
        $form = $this->createForm(I18nType::class);

        return $this->render('I18n/dropdown.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
