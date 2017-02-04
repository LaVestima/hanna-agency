<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Products
 *
 * @ORM\Table(name="Products", uniqueConstraints={@ORM\UniqueConstraint(name="Products_NameU", columns={"Name"}), @ORM\UniqueConstraint(name="Products_Path_SlugU", columns={"Path_Slug"}), @ORM\UniqueConstraint(name="Products_QR_CodeU", columns={"QR_Code"})}, indexes={@ORM\Index(name="FK_4ACC380C3FA94B8D", columns={"ID_CATEGORIES"}), @ORM\Index(name="FK_4ACC380CCEA6C35A", columns={"ID_PRODUCERS"}), @ORM\Index(name="FK_4ACC380C99845F23", columns={"ID_SIZES"})})
 * @ORM\Entity
 */
class Products
{
    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Path_Slug", type="string", length=50, nullable=false)
     */
    private $pathSlug;

    /**
     * @var string
     *
     * @ORM\Column(name="Price_Producer", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $priceProducer;

    /**
     * @var string
     *
     * @ORM\Column(name="Price_Customer", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $priceCustomer;

    /**
     * @var string
     *
     * @ORM\Column(name="QR_Code", type="string", length=50, nullable=false)
     */
    private $qrCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="Availability", type="integer", nullable=false)
     */
    private $availability = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Categories
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Categories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CATEGORIES", referencedColumnName="ID")
     * })
     */
    private $idCategories;

    /**
     * @var \AppBundle\Entity\Sizes
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sizes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_SIZES", referencedColumnName="ID")
     * })
     */
    private $idSizes;

    /**
     * @var \AppBundle\Entity\Producers
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Producers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCERS", referencedColumnName="ID")
     * })
     */
    private $idProducers;



    /**
     * Set name
     *
     * @param string $name
     *
     * @return Products
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set pathSlug
     *
     * @param string $pathSlug
     *
     * @return Products
     */
    public function setPathSlug($pathSlug)
    {
        $this->pathSlug = $pathSlug;

        return $this;
    }

    /**
     * Get pathSlug
     *
     * @return string
     */
    public function getPathSlug()
    {
        return $this->pathSlug;
    }

    /**
     * Set priceProducer
     *
     * @param string $priceProducer
     *
     * @return Products
     */
    public function setPriceProducer($priceProducer)
    {
        $this->priceProducer = $priceProducer;

        return $this;
    }

    /**
     * Get priceProducer
     *
     * @return string
     */
    public function getPriceProducer()
    {
        return $this->priceProducer;
    }

    /**
     * Set priceCustomer
     *
     * @param string $priceCustomer
     *
     * @return Products
     */
    public function setPriceCustomer($priceCustomer)
    {
        $this->priceCustomer = $priceCustomer;

        return $this;
    }

    /**
     * Get priceCustomer
     *
     * @return string
     */
    public function getPriceCustomer()
    {
        return $this->priceCustomer;
    }

    /**
     * Set qrCode
     *
     * @param string $qrCode
     *
     * @return Products
     */
    public function setQrCode($qrCode)
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    /**
     * Get qrCode
     *
     * @return string
     */
    public function getQrCode()
    {
        return $this->qrCode;
    }

    /**
     * Set availability
     *
     * @param integer $availability
     *
     * @return Products
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idCategories
     *
     * @param \AppBundle\Entity\Categories $idCategories
     *
     * @return Products
     */
    public function setIdCategories(\AppBundle\Entity\Categories $idCategories = null)
    {
        $this->idCategories = $idCategories;

        return $this;
    }

    /**
     * Get idCategories
     *
     * @return \AppBundle\Entity\Categories
     */
    public function getIdCategories()
    {
        return $this->idCategories;
    }

    /**
     * Set idSizes
     *
     * @param \AppBundle\Entity\Sizes $idSizes
     *
     * @return Products
     */
    public function setIdSizes(\AppBundle\Entity\Sizes $idSizes = null)
    {
        $this->idSizes = $idSizes;

        return $this;
    }

    /**
     * Get idSizes
     *
     * @return \AppBundle\Entity\Sizes
     */
    public function getIdSizes()
    {
        return $this->idSizes;
    }

    /**
     * Set idProducers
     *
     * @param \AppBundle\Entity\Producers $idProducers
     *
     * @return Products
     */
    public function setIdProducers(\AppBundle\Entity\Producers $idProducers = null)
    {
        $this->idProducers = $idProducers;

        return $this;
    }

    /**
     * Get idProducers
     *
     * @return \AppBundle\Entity\Producers
     */
    public function getIdProducers()
    {
        return $this->idProducers;
    }
}
