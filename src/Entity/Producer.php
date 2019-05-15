<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Producer_Path_Slug_U", columns={"path_slug"})
 * }, indexes={
 *     @ORM\Index(name="Producer_Country_FK", columns={"country_id"}),
 *     @ORM\Index(name="Producer_City_FK", columns={"city_id"}),
 *     @ORM\Index(name="Producer_User_FK", columns={"user_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ProducerRepository")
 */
class Producer implements EntityInterface
{
    /**
     * @var integer
     *
     * @Groups({"api"})
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     * @Groups({"api"})
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $shortName;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $fullName;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $vat;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $street;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $pathSlug = '';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="producers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="producers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="producer", orphanRemoval=true)
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Opinion", mappedBy="producer", orphanRemoval=true)
     */
    private $opinions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="producers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $frontpage_html;


    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->products = new ArrayCollection();
        $this->opinions = new ArrayCollection();
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
     * @return Producer
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
     * @return Producer
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
     * @return Producer
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
     * @return Producer
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
     * Set shortName
     *
     * @param string $shortName
     *
     * @return Producer
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get shortName
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return Producer
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Producer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Producer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set vat
     *
     * @param string $vat
     *
     * @return Producer
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat
     *
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Producer
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Producer
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Producer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Producer
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set pathSlug
     *
     * @param string $pathSlug
     *
     * @return Producer
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

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setProducer($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getProducer() === $this) {
                $product->setProducer(null);
            }
        }

        return $this;
    }

    public function getActiveProducts()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('active', true));

        return $this->products->matching($criteria);
    }

    /**
     * @return Collection|Opinion[]
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinion $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions[] = $opinion;
            $opinion->setProducer($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->contains($opinion)) {
            $this->opinions->removeElement($opinion);
            // set the owning side to null (unless already changed)
            if ($opinion->getProducer() === $this) {
                $opinion->setProducer(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFrontpageHtml(): ?string
    {
        return $this->frontpage_html;
    }

    public function setFrontpageHtml(?string $frontpage_html): self
    {
        $this->frontpage_html = $frontpage_html;

        return $this;
    }
}
