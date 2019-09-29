<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PostLoad;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Product_Path_Slug_U", columns={"path_slug"}),
 * }, indexes={
 *     @ORM\Index(name="Product_Category_FK", columns={"category_id"}),
 *     @ORM\Index(name="Product_Store_FK", columns={"store_id"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @HasLifecycleCallbacks
 */
class Product implements EntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Groups({"api"})
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDeleted;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $userCreated = '0';

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userDeleted;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     *
     * @Groups({"api"})
     */
    private $name;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=false)
     */
    private $price;

    private $oldPrice;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    private $pathSlug = '';

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $active = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"api"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="Store", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"api"})
     */
    private $store;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ProductParameter", mappedBy="product", orphanRemoval=true, cascade={"persist"})
     */
    private $productParameters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductImage", mappedBy="product", orphanRemoval=true, fetch="EAGER", cascade={"persist"})
     */
//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\ProductImage", mappedBy="product", orphanRemoval=true, cascade={"persist"})
//     */
    private $productImages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductVariant", mappedBy="product", orphanRemoval=true, cascade={"persist"})
     */
    private $productVariants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductShipmentOption", mappedBy="product", orphanRemoval=true)
     */
    private $productShipmentOptions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductReview", mappedBy="product", orphanRemoval=true)
     */
    private $productReviews;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductPromotion", mappedBy="product", orphanRemoval=true, cascade={"persist"})
     */
    private $productPromotions;

    public function __construct()
    {
        $this->productParameters = new ArrayCollection();
        $this->productImages = new ArrayCollection();
        $this->productVariants = new ArrayCollection();
        $this->productShipmentOptions = new ArrayCollection();
        $this->productReviews = new ArrayCollection();
        $this->productPromotions = new ArrayCollection();


    }

    /** @PostLoad */
    public function postLoad()
    {
        foreach ($this->getProductPromotions() as $productPromotion) {
            if (
                $productPromotion->getStartDate() < (new \Datetime()) &&
                $productPromotion->getEndDate() > (new \Datetime())
            ) {
                $this->oldPrice = $this->price;
                $this->price = $productPromotion->getDiscountedPrice();

                break;
            }
        }
    }

    public function addProductParameter(ProductParameter $productParameter)
    {
        $productParameter->setProduct($this);
        $this->productParameters->add($productParameter);
    }

    public function removeProductParameter(ProductParameter $productParameter)
    {
        $this->productParameters->removeElement($productParameter);
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
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
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
     * @return bool
     */
    public function getActive(): bool
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

//    /**
//     * @return mixed
//     */
//    public function getProductSizes()
//    {
//        return $this->productSizes;
//    }
//
//    /**
//     * @param mixed $productSizes
//     */
//    public function setProductSizes($productSizes): void
//    {
//        $this->productSizes = $productSizes;
//    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }

    /**
     * @return Collection|ProductParameter[]
     */
    public function getProductParameters(): Collection
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

    /**
     * @return Collection|ProductImage[]
     */
    public function getProductImages(): Collection
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

    public function addProductImage(ProductImage $productImage): self
    {
        if (!$this->productImages->contains($productImage)) {
            $this->productImages[] = $productImage;
            $productImage->setProduct($this);
        }

        return $this;
    }

    public function removeProductImage(ProductImage $productImage): self
    {
        if ($this->productImages->contains($productImage)) {
            $this->productImages->removeElement($productImage);
            // set the owning side to null (unless already changed)
            if ($productImage->getProduct() === $this) {
                $productImage->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductVariant[]
     */
    public function getProductVariants(): Collection
    {
        return $this->productVariants;
    }

    public function addProductVariant(ProductVariant $productVariant): self
    {
        if (!$this->productVariants->contains($productVariant)) {
            $this->productVariants[] = $productVariant;
            $productVariant->setProduct($this);
        }

        return $this;
    }

    public function removeProductVariant(ProductVariant $productVariant): self
    {
        if ($this->productVariants->contains($productVariant)) {
            $this->productVariants->removeElement($productVariant);
            // set the owning side to null (unless already changed)
            if ($productVariant->getProduct() === $this) {
                $productVariant->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductShipmentOption[]
     */
    public function getProductShipmentOptions(): Collection
    {
        return $this->productShipmentOptions;
    }

    public function addProductShipmentOption(ProductShipmentOption $productShipmentOption): self
    {
        if (!$this->productShipmentOptions->contains($productShipmentOption)) {
            $this->productShipmentOptions[] = $productShipmentOption;
            $productShipmentOption->setProduct($this);
        }

        return $this;
    }

    public function removeProductShipmentOption(ProductShipmentOption $productShipmentOption): self
    {
        if ($this->productShipmentOptions->contains($productShipmentOption)) {
            $this->productShipmentOptions->removeElement($productShipmentOption);
            // set the owning side to null (unless already changed)
            if ($productShipmentOption->getProduct() === $this) {
                $productShipmentOption->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductReview[]
     */
    public function getProductReviews(): Collection
    {
        return $this->productReviews;
    }

    public function addProductReview(ProductReview $productReview): self
    {
        if (!$this->productReviews->contains($productReview)) {
            $this->productReviews[] = $productReview;
            $productReview->setProduct($this);
        }

        return $this;
    }

    public function removeProductReview(ProductReview $productReview): self
    {
        if ($this->productReviews->contains($productReview)) {
            $this->productReviews->removeElement($productReview);
            // set the owning side to null (unless already changed)
            if ($productReview->getProduct() === $this) {
                $productReview->setProduct(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ProductPromotion[]
     */
    public function getProductPromotions(): Collection
    {
        return $this->productPromotions;
    }

    public function addProductPromotion(ProductPromotion $productPromotion): self
    {
        if (!$this->productPromotions->contains($productPromotion)) {
            $this->productPromotions[] = $productPromotion;
            $productPromotion->setProduct($this);
        }

        return $this;
    }

    public function removeProductPromotion(ProductPromotion $productPromotion): self
    {
        if ($this->productPromotions->contains($productPromotion)) {
            $this->productPromotions->removeElement($productPromotion);
            // set the owning side to null (unless already changed)
            if ($productPromotion->getProduct() === $this) {
                $productPromotion->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    /**
     * @param mixed $oldPrice
     */
    public function setOldPrice($oldPrice): void
    {
        $this->oldPrice = $oldPrice;
    }
}
