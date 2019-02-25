<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Products
 *
 * @ORM\Table(name="Products", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Products_Name_U", columns={"Name"}),
 *     @ORM\UniqueConstraint(name="Products_Path_Slug_U", columns={"Path_Slug"}),
 *     @ORM\UniqueConstraint(name="Products_QR_Code_Path_U", columns={"QR_Code_Path"})
 * }, indexes={
 *     @ORM\Index(name="Items_ID_CATEGORIES_FK", columns={"ID_CATEGORIES"}),
 *     @ORM\Index(name="Items_ID_PRODUCERS_FK", columns={"ID_PRODUCERS"}),
 *     @ORM\Index(name="Items_ID_SIZES_FK", columns={"ID_SIZES"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product implements EntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"api"})
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_Created", type="datetime", nullable=false)
     */
    private $dateCreated;

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
     *
     * @Groups({"api"})
     */
    private $name;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(name="Price_Producer", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $priceProducer;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(name="Price_Customer", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $priceCustomer;

    /**
     * @var string
     *
     * @ORM\Column(name="Path_Slug", type="string", length=50, nullable=false)
     */
    private $pathSlug = '';

    /**
     * @var Category
     *
     * @Groups({"api"})
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CATEGORIES", referencedColumnName="ID")
     * })
     */
    private $idCategories;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false)
     */
    private $active = false;

    /**
     * @var Producer
     *
     * @Groups({"api"})
     *
     * @ORM\ManyToOne(targetEntity="Producer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCERS", referencedColumnName="ID")
     * })
     */
    private $idProducers;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProductImage", mappedBy="idProducts")
     */
    private $productImages;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProductSize", mappedBy="idProducts", fetch="EAGER", cascade={"persist"}, orphanRemoval=true)
     */
    private $productSizes;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProductParameter", mappedBy="idProducts", fetch="EAGER")
     */
    private $productParameters;


    public function addProductSize(ProductSize $productSize)
    {
        $productSize->setIdProducts($this);
        $this->productSizes->add($productSize);
    }

    public function removeProductSize(ProductSize $productSize)
    {
        $this->productSizes->removeElement($productSize);

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
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * Set pathSlug
     *
     * @param string $pathSlug
     *
     * @return Product
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
     * @param Category $idCategories
     *
     * @return Product
     */
    public function setIdCategories(Category $idCategories = null)
    {
        $this->idCategories = $idCategories;

        return $this;
    }

    /**
     * Get idCategories
     *
     * @return Category
     */
    public function getIdCategories()
    {
        return $this->idCategories;
    }

    /**
     * Set idProducers
     *
     * @param Producer $idProducers
     *
     * @return Product
     */
    public function setIdProducers(Producer $idProducers = null)
    {
        $this->idProducers = $idProducers;

        return $this;
    }

    /**
     * Get idProducers
     *
     * @return Producer
     */
    public function getIdProducers()
    {
        return $this->idProducers;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Product
     */
    public function setActive(bool $active): Product
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductImages()
    {
        return $this->productImages;
    }

    /**
     * @param mixed $productImages
     */
    public function setProductImages($productImages): void
    {
        $this->productImages = $productImages;
    }

    /**
     * @return mixed
     */
    public function getProductSizes()
    {
        return $this->productSizes;
    }

    /**
     * @param mixed $productSizes
     */
    public function setProductSizes($productSizes): void
    {
        $this->productSizes = $productSizes;
    }

    /**
     * @return mixed
     */
    public function getProductParameters()
    {
        return $this->productParameters;
    }

    /**
     * @param mixed $productParameters
     */
    public function setProductParameters($productParameters): void
    {
        $this->productParameters = $productParameters;
    }
}
