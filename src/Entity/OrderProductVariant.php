<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * OrdersProducts
 *
 * @ORM\Table(indexes={
 *     @ORM\Index(name="Orders_Products_ID_ORDERS_FK", columns={"order_id"}),
 *     @ORM\Index(name="Orders_Products_ID_PRODUCTS_SIZES_FK", columns={"product_variant_id"}),
 *     @ORM\Index(name="Orders_Products_ID_STATUSES_FK", columns={"status_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\OrderProductRepository")
 */
class OrderProductVariant implements EntityInterface
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
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $discount = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderProductVariants", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductVariant", inversedBy="orderProductVariants", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productVariant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderStatus", inversedBy="orderProductVariants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return OrderProductVariant
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     *
     * @return OrderProductVariant
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return OrderProductVariant
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getProductVariant(): ?ProductVariant
    {
        return $this->productVariant;
    }

    public function setProductVariant(?ProductVariant $productVariant): self
    {
        $this->productVariant = $productVariant;

        return $this;
    }

    public function getStatus(): ?OrderStatus
    {
        return $this->status;
    }

    public function setStatus(?OrderStatus $status): self
    {
        $this->status = $status;

        return $this;
    }
}
