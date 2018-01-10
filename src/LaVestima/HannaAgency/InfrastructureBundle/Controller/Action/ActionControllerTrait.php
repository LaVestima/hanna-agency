<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Action;

trait ActionControllerTrait
{
    protected $entityName;
    protected $entityCrudController;

    protected $view;
    protected $actionBar;
    protected $templateEntities;

    /**
     * Base view render.
     *
     * @return mixed
     */
    protected function baseRender()
    {
        return $this->render($this->view, $this->templateEntities);
    }

    /**
     * Set entity name.
     *
     * @param string $entityName
     * @return $this
     */
    public function setEntityName(string $entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * Set view template.
     *
     * @param string $view
     * @return $this
     */
    public function setView(string $view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Set action bar elements.
     *
     * @param array $actionBar
     * @return $this
     */
    public function setActionBar(array $actionBar)
    {
        $this->actionBar = $actionBar;

        return $this;
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

    /**
     * Add template entities.
     *
     * @param array $templateEntities
     */
    protected function addTemplateEntities(array $templateEntities)
    {
        $this->templateEntities = array_merge(
            $this->templateEntities ?? [],
            $templateEntities
        );
    }
}
