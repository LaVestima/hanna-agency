<?php

namespace AppBundle\Entity;

/**
 * InvoicesProducts
 */
class InvoicesProducts
{
    /**
     * @var integer
     */
    private $quantity;

    /**
     * @var integer
     */
    private $discount;

    /**
     * @var string
     */
    private $priceFinal;

    /**
     * @var string
     */
    private $note;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Invoices
     */
    private $idInvoices;

    /**
     * @var \AppBundle\Entity\Products
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
