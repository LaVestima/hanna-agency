<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Shipment_Option_Name_U", columns={"name"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ShipmentOptionRepository")
 */
class ShipmentOption implements EntityInterface
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
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=false)
     */
    private $cost;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductShipmentOption", mappedBy="shipment_option", orphanRemoval=true)
     */
    private $productShipmentOptions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="shipmentOption", orphanRemoval=true)
     */
    private $orders;


    public function __construct()
    {
        $this->productShipmentOptions = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): ShipmentOption
    {
        $this->name = $name;

        return $this;
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
    public function setCost(string $cost): ShipmentOption
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return Collection|ProductShipmentOption[]
     */
    public function getProductShipmentOptions(): Collection
    {
        return $this->productShipmentOptions;
    }

    public function addProductShipmentOption(ProductShipmentOption $productShipmentOption): self
    {
        if (!$this->productShipmentOptions->contains($productShipmentOption)) {
            $this->productShipmentOptions[] = $productShipmentOption;
            $productShipmentOption->setShipmentOption($this);
        }

        return $this;
    }

    public function removeProductShipmentOption(ProductShipmentOption $productShipmentOption): self
    {
        if ($this->productShipmentOptions->contains($productShipmentOption)) {
            $this->productShipmentOptions->removeElement($productShipmentOption);
            // set the owning side to null (unless already changed)
            if ($productShipmentOption->getShipmentOption() === $this) {
                $productShipmentOption->setShipmentOption(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setShipmentOption($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getShipmentOption() === $this) {
                $order->setShipmentOption(null);
            }
        }

        return $this;
    }
}
