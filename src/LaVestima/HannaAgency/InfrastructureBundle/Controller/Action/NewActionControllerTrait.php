<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Action;

use Symfony\Component\Form\FormInterface;

trait NewActionControllerTrait
{
    protected $form;

    /**
     * Base New Action.
     *
     * @return mixed
     * @throws \Exception
     */
    public function baseNewAction()
    {
        if (!isset($this->view)) {
            throw new \Exception('No view defined!');
        }
        if (!isset($this->form)) {
            throw new \Exception('No form defined!');
        }

        $this->addTemplateEntities([
            'actionBar' => $this->actionBar,
            'form' => $this->form->createView(),
        ]);

        return $this->baseRender();
    }

    /**
     * Set Entity Form.
     *
     * @param FormInterface $form
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;
    }
}
