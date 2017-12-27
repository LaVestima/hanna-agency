<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Action;

trait ShowActionControllerTrait
{
    protected $templateEntities;

    /**
     * Show Action.
     *
     * @return mixed
     * @throws \Exception
     */
    public function baseShowAction()
    {
        if (!isset($this->view)) {
            throw new \Exception('No view defined!');
        }
        if (!isset($this->templateEntities) && !empty($this->templateEntities)) {
            throw new \Exception('No entities defined!');
        }

        $this->templateEntities['actionBar'] = $this->actionBar;

        return $this->render($this->view, $this->templateEntities);
    }

    /**
     * Set template entities;
     *
     * @param array $templateEntities
     */
    protected function setTemplateEntities(array $templateEntities)
    {
        $this->templateEntities = $templateEntities;
    }
}
