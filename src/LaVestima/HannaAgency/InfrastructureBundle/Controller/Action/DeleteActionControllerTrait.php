<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Action;

trait DeleteActionControllerTrait
{
    /**
     * Delete Action.
     *
     * @param string $pathSlug
     * @return mixed
     */
    public function deleteAction(string $pathSlug)
    {
        if (!isset($this->entityName)) {
            throw new \Exception('No entity name defined!');
        }

        $crudControllerName = $this->entityName . 'CrudController';

        $entity = $this->$crudControllerName
            ->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getResult();

        if (!$entity || !$this->isUserAllowedToViewEntity($entity)) {
            $this->addFlash('warning', 'No ' . $this->entityName . ' found!');
        } else {
            $this->$crudControllerName->deleteEntity($entity);

            $this->addFlash('notice', ucfirst($this->entityName) . ' deleted!');
        }

        return $this->redirectToRoute($this->entityName . '_list');
    }
}