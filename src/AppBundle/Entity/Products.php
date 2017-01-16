<?php

namespace AppBundle\Entity;

/**
 * Products
 */
class Products
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $priceProducer;

    /**
     * @var string
     */
    private $priceCustomer;

    /**
     * @var string
     */
    private $qrCode;

    /**
     * @var integer
     */
    private $availability = '0';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Categories
     */
    private $idCategories;

    /**
     * @var \AppBundle\Entity\Producers
     */
    private $idProducers;

    /**
     * @var \AppBundle\Entity\Sizes
     */
    private $idSizes;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Products
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set priceProducer
     *
     * @param string $priceProducer
     *
     * @return Products
     */
    public function setPriceProducer($priceProducer)
    {
        $this->priceProducer = $priceProducer;

        return $this;
    }

    /**
     * Get priceProducer
     *
     * @return string
     */
    public function getPriceProducer()
    {
        return $this->priceProducer;
    }

    /**
     * Set priceCustomer
     *
     * @param string $priceCustomer
     *
     * @return Products
     */
    public function setPriceCustomer($priceCustomer)
    {
        $this->priceCustomer = $priceCustomer;

        return $this;
    }

    /**
     * Get priceCustomer
     *
     * @return string
     */
    public function getPriceCustomer()
    {
        return $this->priceCustomer;
    }

    /**
     * Set qrCode
     *
     * @param string $qrCode
     *
     * @return Products
     */
    public function setQrCode($qrCode)
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    /**
     * Get qrCode
     *
     * @return string
     */
    public function getQrCode()
    {
        return $this->qrCode;
    }

    /**
     * Set availability
     *
     * @param integer $availability
     *
     * @return Products
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
     * Set idCategories
     *
     * @param \AppBundle\Entity\Categories $idCategories
     *
     * @return Products
     */
    public function setIdCategories(\AppBundle\Entity\Categories $idCategories = null)
    {
        $this->idCategories = $idCategories;

        return $this;
    }

    /**
     * Get idCategories
     *
     * @return \AppBundle\Entity\Categories
     */
    public function getIdCategories()
    {
        return $this->idCategories;
    }

    /**
     * Set idProducers
     *
     * @param \AppBundle\Entity\Producers $idProducers
     *
     * @return Products
     */
    public function setIdProducers(\AppBundle\Entity\Producers $idProducers = null)
    {
        $this->idProducers = $idProducers;

        return $this;
    }

    /**
     * Get idProducers
     *
     * @return \AppBundle\Entity\Producers
     */
    public function getIdProducers()
    {
        return $this->idProducers;
    }

    /**
     * Set idSizes
     *
     * @param \AppBundle\Entity\Sizes $idSizes
     *
     * @return Products
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
    /**
     * @var string
     */
    private $pathSlug;


    /**
     * Set pathSlug
     *
     * @param string $pathSlug
     *
     * @return Products
     */
    public function setPathSlug($pathSlug)
    {
        $this->pathSlug = $pathSlug;

        return $this;
    }

    /**
     * Get pathSlug
     *
     * @return string
     */
    public function getPathSlug()
    {
        return $this->pathSlug;
    }
}
