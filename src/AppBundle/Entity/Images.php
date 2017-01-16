<?php

namespace AppBundle\Entity;

/**
 * Images
 */
class Images
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Products
     */
    private $idProducts;


    /**
     * Set filePath
     *
     * @param string $filePath
     *
     * @return Images
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
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
     * @return Images
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
}
