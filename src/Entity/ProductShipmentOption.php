<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsShipmentOptions
 *
 * @ORM\Table(indexes={
 *     @ORM\Index(name="Product_Shipment_Option_ID_PRODUCTS_FK", columns={"product_id"}),
 *     @ORM\Index(name="Product_Shipment_Option_ID_SHIPMENT_OPTIONS_FK", columns={"shipment_option_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ProductShipmentOptionRepository")
 */
class ProductShipmentOption implements EntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Cost", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productShipmentOptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ShipmentOption", inversedBy="productShipmentOptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shipment_option;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getShipmentOption(): ?ShipmentOption
    {
        return $this->shipment_option;
    }

    public function setShipmentOption(?ShipmentOption $shipment_option): self
    {
        $this->shipment_option = $shipment_option;

        return $this;
    }
}
