<?php

namespace LaVestima\HannaAgency\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;
use LaVestima\HannaAgency\ProducerBundle\Entity\Producers;

/**
 * Products
 *
 * @ORM\Table(name="Products", uniqueConstraints={@ORM\UniqueConstraint(name="Products_Name_U", columns={"Name"}), @ORM\UniqueConstraint(name="Products_Path_Slug_U", columns={"Path_Slug"}), @ORM\UniqueConstraint(name="Products_QR_Code_Path_U", columns={"QR_Code_Path"})}, indexes={@ORM\Index(name="Items_ID_CATEGORIES_FK", columns={"ID_CATEGORIES"}), @ORM\Index(name="Items_ID_PRODUCERS_FK", columns={"ID_PRODUCERS"}), @ORM\Index(name="Items_ID_SIZES_FK", columns={"ID_SIZES"})})
 * @ORM\Entity
 */
class Products implements EntityInterface
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
     * @var \DateTime
     *
     * @ORM\Column(name="Date_Created", type="datetime", nullable=false)
     */
    private $dateCreated = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_Deleted", type="datetime", nullable=true)
     */
    private $dateDeleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="User_Created", type="integer", nullable=false)
     */
    private $userCreated = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="User_Deleted", type="integer", nullable=true)
     */
    private $userDeleted;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=50, nullable=false)
     */
    private $name;

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
     * @ORM\Column(name="QR_Code_Path", type="string", length=50, nullable=false)
     */
    private $qrCodePath;

    /**
     * @var string
     *
     * @ORM\Column(name="Path_Slug", type="string", length=50, nullable=false)
     */
    private $pathSlug = '';

    /**
     * @var Categories
     *
     * @ORM\ManyToOne(targetEntity="Categories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CATEGORIES", referencedColumnName="ID")
     * })
     */
    private $idCategories;

    /**
     * @var Producers
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\ProducerBundle\Entity\Producers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCERS", referencedColumnName="ID")
     * })
     */
    private $idProducers;

    /**
     * @var Sizes
     *
     * @ORM\ManyToOne(targetEntity="Sizes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_SIZES", referencedColumnName="ID")
     * })
     */
    private $idSizes;



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
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Products
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateDeleted
     *
     * @param \DateTime $dateDeleted
     *
     * @return Products
     */
    public function setDateDeleted($dateDeleted)
    {
        $this->dateDeleted = $dateDeleted;

        return $this;
    }

    /**
     * Get dateDeleted
     *
     * @return \DateTime
     */
    public function getDateDeleted()
    {
        return $this->dateDeleted;
    }

    /**
     * Set userCreated
     *
     * @param integer $userCreated
     *
     * @return Products
     */
    public function setUserCreated($userCreated)
    {
        $this->userCreated = $userCreated;

        return $this;
    }

    /**
     * Get userCreated
     *
     * @return integer
     */
    public function getUserCreated()
    {
        return $this->userCreated;
    }

    /**
     * Set userDeleted
     *
     * @param integer $userDeleted
     *
     * @return Products
     */
    public function setUserDeleted($userDeleted)
    {
        $this->userDeleted = $userDeleted;

        return $this;
    }

    /**
     * Get userDeleted
     *
     * @return integer
     */
    public function getUserDeleted()
    {
        return $this->userDeleted;
    }

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
     * Set qrCodePath
     *
     * @param string $qrCodePath
     *
     * @return Products
     */
    public function setQrCodePath($qrCodePath)
    {
        $this->qrCodePath = $qrCodePath;

        return $this;
    }

    /**
     * Get qrCodePath
     *
     * @return string
     */
    public function getQrCodePath()
    {
        return $this->qrCodePath;
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
     * Set idCategories
     *
     * @param Categories $idCategories
     *
     * @return Products
     */
    public function setIdCategories(Categories $idCategories = null)
    {
        $this->idCategories = $idCategories;

        return $this;
    }

    /**
     * Get idCategories
     *
     * @return Categories
     */
    public function getIdCategories()
    {
        return $this->idCategories;
    }

    /**
     * Set idProducers
     *
     * @param Producers $idProducers
     *
     * @return Products
     */
    public function setIdProducers(Producers $idProducers = null)
    {
        $this->idProducers = $idProducers;

        return $this;
    }

    /**
     * Get idProducers
     *
     * @return Producers
     */
    public function getIdProducers()
    {
        return $this->idProducers;
    }

    /**
     * Set idSizes
     *
     * @param Sizes $idSizes
     *
     * @return Products
     */
    public function setIdSizes(Sizes $idSizes = null)
    {
        $this->idSizes = $idSizes;

        return $this;
    }

    /**
     * Get idSizes
     *
     * @return Sizes
     */
    public function getIdSizes()
    {
        return $this->idSizes;
    }
}
