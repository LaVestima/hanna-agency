<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoicesProducts
 *
 * @ORM\Table(name="Invoices_Products", indexes={@ORM\Index(name="FK_7A615D543506D34B", columns={"ID_INVOICES"}), @ORM\Index(name="FK_7A615D54EC93A684", columns={"ID_PRODUCTS"})})
 * @ORM\Entity
 */
class InvoicesProducts
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
     * @var \AppBundle\Entity\Invoices
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Invoices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_INVOICES", referencedColumnName="ID")
     * })
     */
    private $idInvoices;

    /**
     * @var \AppBundle\Entity\Products
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTS", referencedColumnName="ID")
     * })
     */
    private $idProducts;



    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return InvoicesProducts
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
     * @return InvoicesProducts
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
     * @return InvoicesProducts
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
     * @return InvoicesProducts
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
     * Set idInvoices
     *
     * @param \AppBundle\Entity\Invoices $idInvoices
     *
     * @return InvoicesProducts
     */
    public function setIdInvoices(\AppBundle\Entity\Invoices $idInvoices = null)
    {
        $this->idInvoices = $idInvoices;

        return $this;
    }

    /**
     * Get idInvoices
     *
     * @return \AppBundle\Entity\Invoices
     */
    public function getIdInvoices()
    {
        return $this->idInvoices;
    }

    /**
     * Set idProducts
     *
     * @param \AppBundle\Entity\Products $idProducts
     *
     * @return InvoicesProducts
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
