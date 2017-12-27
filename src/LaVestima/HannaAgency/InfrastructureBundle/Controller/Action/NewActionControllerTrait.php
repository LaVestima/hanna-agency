<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Action;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

trait NewActionControllerTrait
{
    protected $form;

    public function baseNewAction(Request $request)
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
