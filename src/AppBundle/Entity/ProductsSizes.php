<?php

namespace AppBundle\Entity;

/**
 * ProductsSizes
 */
class ProductsSizes
{
    /**
     * @var integer
     */
    private $availability;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Products
     */
    private $idProducts;

    /**
     * @var \AppBundle\Entity\Sizes
     */
    private $idSizes;


    /**
     * Set availability
     *
     * @param integer $availability
     *
     * @return ProductsSizes
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * Get availability
     *
     * @return integer
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idProducts
     *
     * @param \AppBundle\Entity\Products $idProducts
     *
     * @return ProductsSizes
     */
    public function setIdProducts(\AppBundle\Entity\Products $idProducts = null)
    {
        $this->idProducts = $idProducts;

        return $this;
    }

    /**
     * Get idProducts
     *
     * @return \AppBundle\Entity\Products
     */
    public function getIdProducts()
    {
        return $this->idProducts;
    }

    /**
     * Set idSizes
     *
     * @param \AppBundle\Entity\Sizes $idSizes
     *
     * @return ProductsSizes
     */
    public function setIdSizes(\AppBundle\Entity\Sizes $idSizes = null)
    {
        $this->idSizes = $idSizes;

        return $this;
    }

    /**
     * Get idSizes
     *
     * @return \AppBundle\Entity\Sizes
     */
    public function getIdSizes()
    {
        return $this->idSizes;
    }
}
