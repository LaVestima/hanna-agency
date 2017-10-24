<?php

namespace LaVestima\HannaAgency\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;
use LaVestima\HannaAgency\ProductBundle\Entity\ProductsSizes;

/**
 * OrdersProducts
 *
 * @ORM\Table(name="Orders_Products", indexes={@ORM\Index(name="Orders_Products_ID_ORDERS_FK", columns={"ID_ORDERS"}), @ORM\Index(name="Orders_Products_ID_PRODUCTS_SIZES_FK", columns={"ID_PRODUCTS_SIZES"}), @ORM\Index(name="Orders_Products_ID_STATUSES_FK", columns={"ID_STATUSES"})})
 * @ORM\Entity
 */
class OrdersProducts implements EntityInterface, \JsonSerializable
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
     * @ORM\Column(name="Quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="Discount", type="integer", nullable=false)
     */
    private $discount = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="Note", type="string", length=200, nullable=true)
     */
    private $note;

    /**
     * @var Orders
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\OrderBundle\Entity\Orders", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_ORDERS", referencedColumnName="ID")
     * })
     */
    private $idOrders;

    /**
     * @var ProductsSizes
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\ProductBundle\Entity\ProductsSizes", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTS_SIZES", referencedColumnName="ID")
     * })
     */
    private $idProductsSizes;

    /**
     * @var OrdersStatuses
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\OrderBundle\Entity\OrdersStatuses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_STATUSES", referencedColumnName="ID")
     * })
     */
    private $idStatuses;



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
     * @return OrdersProducts
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
     * @return OrdersProducts
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
     * @return OrdersProducts
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

    /**
     * Set idOrders
     *
     * @param Orders $idOrders
     *
     * @return OrdersProducts
     */
    public function setIdOrders(Orders $idOrders = null)
    {
        $this->idOrders = $idOrders;

        return $this;
    }

    /**
     * Get idOrders
     *
     * @return Orders
     */
    public function getIdOrders()
    {
        return $this->idOrders;
    }

    /**
     * Set idProductsSizes
     *
     * @param ProductsSizes $idProductsSizes
     *
     * @return OrdersProducts
     */
    public function setIdProductsSizes(ProductsSizes $idProductsSizes = null)
    {
        $this->idProductsSizes = $idProductsSizes;

        return $this;
    }

    /**
     * Get idProductsSizes
     *
     * @return ProductsSizes
     */
    public function getIdProductsSizes()
    {
        return $this->idProductsSizes;
    }

    /**
     * Set idStatuses
     *
     * @param OrdersStatuses $idStatuses
     *
     * @return OrdersProducts
     */
    public function setIdStatuses(OrdersStatuses $idStatuses = null)
    {
        $this->idStatuses = $idStatuses;

        return $this;
    }

    /**
     * Get idStatuses
     *
     * @return OrdersStatuses
     */
    public function getIdStatuses()
    {
        return $this->idStatuses;
    }

    /**
     * @return array
     */
    public function jsonSerialize() // TODO: finish !!!
    {
        $json = [];
        $json['name'] = $this->idProductsSizes->getName();
        $json['quantity'] = $this->quantity;
        $json['status'] = $this->idStatuses->getName();

        return $json;
    }
}
