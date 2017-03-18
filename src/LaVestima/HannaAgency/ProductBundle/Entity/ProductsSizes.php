<?php

namespace LaVestima\HannaAgency\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsSizes
 *
 * @ORM\Table(name="Products_Sizes", indexes={@ORM\Index(name="Products_Sizes_ID_PRODUCTS_FK", columns={"ID_PRODUCTS"}), @ORM\Index(name="Products_Sizes_ID_SIZES_FK", columns={"ID_SIZES"})})
 * @ORM\Entity
 */
class ProductsSizes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="Availability", type="integer", nullable=false)
     */
    private $availability;

    /**
     * @var \Products
     *
     * @ORM\ManyToOne(targetEntity="Products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTS", referencedColumnName="ID")
     * })
     */
    private $idProducts;

    /**
     * @var \Sizes
     *
     * @ORM\ManyToOne(targetEntity="Sizes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_SIZES", referencedColumnName="ID")
     * })
     */
    private $idSizes;



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
     * Set idProducts
     *
     * @param \LaVestima\HannaAgency\ProductBundle\Entity\Products $idProducts
     *
     * @return ProductsSizes
     */
    public function setIdProducts(\LaVestima\HannaAgency\ProductBundle\Entity\Products $idProducts = null)
    {
        $this->idProducts = $idProducts;

        return $this;
    }

    /**
     * Get idProducts
     *
     * @return \LaVestima\HannaAgency\ProductBundle\Entity\Products
     */
    public function getIdProducts()
    {
        return $this->idProducts;
    }

    /**
     * Set idSizes
     *
     * @param \LaVestima\HannaAgency\ProductBundle\Entity\Sizes $idSizes
     *
     * @return ProductsSizes
     */
    public function setIdSizes(\LaVestima\HannaAgency\ProductBundle\Entity\Sizes $idSizes = null)
    {
        $this->idSizes = $idSizes;

        return $this;
    }

    /**
     * Get idSizes
     *
     * @return \LaVestima\HannaAgency\ProductBundle\Entity\Sizes
     */
    public function getIdSizes()
    {
        return $this->idSizes;
    }
}
