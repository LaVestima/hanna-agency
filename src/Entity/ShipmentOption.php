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


    public function __construct()
    {
        $this->productShipmentOptions = new ArrayCollection();
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
    public function setName(string $name): void
    {
        $this->name = $name;
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
}
