<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsSizes
 *
 * @ORM\Table(name="Products_Sizes", indexes={
 *     @ORM\Index(name="Products_Sizes_ID_PRODUCTS_FK", columns={"ID_PRODUCTS"}),
 *     @ORM\Index(name="Products_Sizes_ID_SIZES_FK", columns={"ID_SIZES"})
 * })
 * @ORM\Entity
 */
class ProductSize implements EntityInterface
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
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTS", referencedColumnName="ID")
     * })
     */
    private $idProducts;

    /**
     * @var Size
     *
     * @ORM\ManyToOne(targetEntity="Size", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_SIZES", referencedColumnName="ID")
     * })
     */
    private $idSizes;

    public function __construct(
        Product $idProducts = null,
        Size $idSizes = null,
        int $availability = 0
    ) {
        $this->idProducts = $idProducts;
        $this->idSizes = $idSizes;
        $this->availability = $availability;
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
     * Set availability
     *
     * @param integer $availability
     *
     * @return ProductSize
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
     * @param Product $idProducts
     *
     * @return ProductSize
     */
    public function setIdProducts(Product $idProducts = null)
    {
        $this->idProducts = $idProducts;

        return $this;
    }

    /**
     * Get idProducts
     *
     * @return Product
     */
    public function getIdProducts()
    {
        return $this->idProducts;
    }

    /**
     * Set idSizes
     *
     * @param Size $idSizes
     *
     * @return ProductSize
     */
    public function setIdSizes(Size $idSizes = null)
    {
        $this->idSizes = $idSizes;

        return $this;
    }

    /**
     * Get idSizes
     *
     * @return Size
     */
    public function getIdSizes()
    {
        return $this->idSizes;
    }
}
