<?php

namespace App\Controller\Infrastructure\Action;

trait ActionControllerTrait
{
    protected $entityName;
    protected $view;
    protected $actionBar;

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
}
