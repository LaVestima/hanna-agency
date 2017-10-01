<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Action;

trait ActionControllerTrait
{
    protected $view;
    protected $actionBar;

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