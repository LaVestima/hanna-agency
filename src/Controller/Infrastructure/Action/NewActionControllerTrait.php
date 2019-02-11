<?php

namespace App\Controller\Infrastructure\Action;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

trait NewActionControllerTrait
{
    protected $form;

    public function baseNew(Request $request)
    {
        if (!isset($this->view)) {
            throw new \Exception('No view defined!');
        }

        return $this->render($this->view, [
            'actionBar' => $this->actionBar,
            'form' => $this->form->createView(),
        ]);
    }

    /**
     * Set Entity Form.
     *
     * @param Form $form
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
    }
}