<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsShipmentOptions
 *
 * @ORM\Table(name="Products_Shipment_Options", indexes={
 *     @ORM\Index(name="Products_Shipment_Options_ID_PRODUCTS_FK", columns={"ID_PRODUCTS"}),
 *     @ORM\Index(name="Products_Shipment_Options_ID_SHIPMENT_OPTIONS_FK", columns={"ID_SHIPMENT_OPTIONS"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ProductShipmentOptionRepository")
 */
class ProductShipmentOption implements EntityInterface
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
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTS", referencedColumnName="ID")
     * })
     */
    private $idProducts;

    /**
     * @var ShipmentOption
     *
     * @ORM\ManyToOne(targetEntity="ShipmentOption", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_SHIPMENT_OPTIONS", referencedColumnName="ID")
     * })
     */
    private $idShipmentOptions;

    /**
     * @var string
     *
     * @ORM\Column(name="Cost", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $cost;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Product
     */
    public function getIdProducts(): Product
    {
        return $this->idProducts;
    }

    /**
     * @param Product $idProducts
     */
    public function setIdProducts(Product $idProducts): void
    {
        $this->idProducts = $idProducts;
    }

    /**
     * @return ShipmentOption
     */
    public function getIdShipmentOptions(): ShipmentOption
    {
        return $this->idShipmentOptions;
    }

    /**
     * @param ShipmentOption $idShipmentOptions
     */
    public function setIdShipmentOptions(ShipmentOption $idShipmentOptions): void
    {
        $this->idShipmentOptions = $idShipmentOptions;
    }

    /**
     * @return string
     */
    public function getCost(): string
    {
        return $this->cost;
    }

    /**
     * @param string $cost
     */
    public function setCost(string $cost): void
    {
        $this->cost = $cost;
    }
}
