<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * OrdersProducts
 *
 * @ORM\Table(name="Orders_Products", indexes={
 *     @ORM\Index(name="Orders_Products_ID_ORDERS_FK", columns={"ID_ORDERS"}),
 *     @ORM\Index(name="Orders_Products_ID_PRODUCTS_SIZES_FK", columns={"ID_PRODUCTS_SIZES"}),
 *     @ORM\Index(name="Orders_Products_ID_STATUSES_FK", columns={"ID_STATUSES"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\OrderProductRepository")
 */
class OrderProduct implements EntityInterface
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
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="orderProducts", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_ORDERS", referencedColumnName="ID")
     * })
     */
    private $idOrders;

    /**
     * @var ProductSize
     *
     * @ORM\ManyToOne(targetEntity="ProductSize", inversedBy="orderProducts", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTS_SIZES")
     * })
     */
    private $idProductsSizes;

    /**
     * @var OrderStatus
     *
     * @ORM\ManyToOne(targetEntity="OrderStatus")
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
     * @return OrderProduct
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
     * @return OrderProduct
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
     * @return OrderProduct
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
     * @param Order $idOrders
     *
     * @return OrderProduct
     */
    public function setIdOrders(Order $idOrders = null)
    {
        $this->idOrders = $idOrders;

        return $this;
    }

    /**
     * Get idOrders
     *
     * @return Order
     */
    public function getIdOrders()
    {
        return $this->idOrders;
    }

    /**
     * Set idProductsSizes
     *
     * @param ProductSize $idProductsSizes
     *
     * @return OrderProduct
     */
    public function setIdProductsSizes(ProductSize $idProductsSizes = null)
    {
        $this->idProductsSizes = $idProductsSizes;

        return $this;
    }

    /**
     * Get idProductsSizes
     *
     * @return ProductSize
     */
    public function getIdProductsSizes()
    {
        return $this->idProductsSizes;
    }

    /**
     * Set idStatuses
     *
     * @param OrderStatus $idStatuses
     *
     * @return OrderProduct
     */
    public function setIdStatuses(OrderStatus $idStatuses = null)
    {
        $this->idStatuses = $idStatuses;

        return $this;
    }

    /**
     * Get idStatuses
     *
     * @return OrderStatus
     */
    public function getIdStatuses()
    {
        return $this->idStatuses;
    }

//    /**
//     * @return array
//     */
//    public function jsonSerialize() // TODO: finish !!!
//    {
//        $json = [];
//        $json['name'] = $this->idProductsSizes->getName();
//        $json['quantity'] = $this->quantity;
//        $json['status'] = $this->idStatuses->getName();
//
//        return $json;
//    }
}