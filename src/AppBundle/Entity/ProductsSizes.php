<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsSizes
 *
 * @ORM\Table(name="Products_Sizes", indexes={@ORM\Index(name="FK_5A622E65EC93A684", columns={"ID_PRODUCTS"}), @ORM\Index(name="FK_5A622E6599845F23", columns={"ID_SIZES"})})
 * @ORM\Entity
 */
class ProductsSizes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Availability", type="integer", nullable=false)
     */
    private $availability;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Sizes
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sizes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_SIZES", referencedColumnName="ID")
     * })
     */
    private $idSizes;

    /**
     * @var \AppBundle\Entity\Products
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTS", referencedColumnName="ID")
     * })
     */
    private $idProducts;



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
}
