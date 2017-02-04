<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * OrdersProducts
 *
 * @ORM\Table(name="Orders_Products", indexes={@ORM\Index(name="FK_52FDB5448676F70", columns={"ID_ORDERS"}), @ORM\Index(name="FK_52FDB54EC93A684", columns={"ID_PRODUCTS"}), @ORM\Index(name="FK_52FDB5414D9E2CF", columns={"ID_STATUSES"})})
 * @ORM\Entity
 */
class OrdersProducts
{
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
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(name="Price_Final", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $priceFinal;

    /**
     * @var string
     *
     * @ORM\Column(name="Note", type="string", length=200, nullable=true)
     */
    private $note;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\OrdersStatuses
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OrdersStatuses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_STATUSES", referencedColumnName="ID")
     * })
     */
    private $idStatuses;

    /**
     * @var \AppBundle\Entity\Orders
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Orders")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_ORDERS", referencedColumnName="ID")
     * })
     */
    private $idOrders;

    /**
     * @var \AppBundle\Entity\Products
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTS", referencedColumnName="ID")
     * })
     */
    private $idProducts;


    public function __construct() {
		$this->idProducts = new ArrayCollection();
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
     * Set priceFinal
     *
     * @param string $priceFinal
     *
     * @return OrdersProducts
     */
    public function setPriceFinal($priceFinal)
    {
        $this->priceFinal = $priceFinal;

        return $this;
    }

    /**
     * Get priceFinal
     *
     * @return string
     */
    public function getPriceFinal()
    {
        return $this->priceFinal;
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idStatuses
     *
     * @param \AppBundle\Entity\OrdersStatuses $idStatuses
     *
     * @return OrdersProducts
     */
    public function setIdStatuses(\AppBundle\Entity\OrdersStatuses $idStatuses = null)
    {
        $this->idStatuses = $idStatuses;

        return $this;
    }

    /**
     * Get idStatuses
     *
     * @return \AppBundle\Entity\OrdersStatuses
     */
    public function getIdStatuses()
    {
        return $this->idStatuses;
    }

    /**
     * Set idOrders
     *
     * @param \AppBundle\Entity\Orders $idOrders
     *
     * @return OrdersProducts
     */
    public function setIdOrders(\AppBundle\Entity\Orders $idOrders = null)
    {
        $this->idOrders = $idOrders;

        return $this;
    }

    /**
     * Get idOrders
     *
     * @return \AppBundle\Entity\Orders
     */
    public function getIdOrders()
    {
        return $this->idOrders;
    }

    /**
     * Set idProducts
     *
     * @param \AppBundle\Entity\Products $idProducts
     *
     * @return OrdersProducts
     */
    public function setIdProducts(\AppBundle\Entity\Products $idProducts = null)
    {
        $this->idProducts = $idProducts;

        return $this;
    }

    /**
     * Get idProducts
     *
     * @return \AppBundle\Entity\Products
     */
    public function getIdProducts()
    {
        return $this->idProducts;
    }
}
