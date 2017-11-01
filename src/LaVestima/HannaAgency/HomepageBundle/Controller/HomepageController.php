<?php

namespace LaVestima\HannaAgency\HomepageBundle\Controller;

use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;

class HomepageController extends BaseController
{
    /**
     * Homepage Action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function homepageAction()
    {
		return $this->render('@Homepage/Homepage/index.html.twig');
	}

    /**
     * Contact Action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function contactAction()
    {
        return $this->render('@Homepage/Homepage/contact.html.twig');
    }
}