<?php

namespace App\Entity;

use App\Helper\RandomHelper;
use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductVariantRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductVariant implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productVariants", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Variant", inversedBy="productVariants", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $variant;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $identifier;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProductVariant", mappedBy="productVariant", orphanRemoval=true)
     */
    private $orderProductVariants;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $availability;


    public function __construct()
    {
        $this->orderProductVariants = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getVariant(): ?Variant
    {
        return $this->variant;
    }

    public function setVariant(?Variant $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return Collection|OrderProductVariant[]
     */
    public function getOrderProductVariants(): Collection
    {
        return $this->orderProductVariants;
    }

    public function addOrderProductVariant(OrderProductVariant $orderProductVariant): self
    {
        if (!$this->orderProductVariants->contains($orderProductVariant)) {
            $this->orderProductVariants[] = $orderProductVariant;
            $orderProductVariant->setProductVariant($this);
        }

        return $this;
    }

    public function removeOrderProductVariant(OrderProductVariant $orderProductVariant): self
    {
        if ($this->orderProductVariants->contains($orderProductVariant)) {
            $this->orderProductVariants->removeElement($orderProductVariant);
            // set the owning side to null (unless already changed)
            if ($orderProductVariant->getProductVariant() === $this) {
                $orderProductVariant->setProductVariant(null);
            }
        }

        return $this;
    }

    /**
     * Set availability
     *
     * @param integer $availability
     *
     * @return ProductVariant
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
     * @ORM\PrePersist
     */
    public function setNewIdentifier()
    {
        $this->setIdentifier(RandomHelper::generateString(50));
    }
}
