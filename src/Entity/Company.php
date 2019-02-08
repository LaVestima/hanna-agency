<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

//use LaVestima\HannaAgency\LocationBundle\Entity\Cities;
//use LaVestima\HannaAgency\LocationBundle\Entity\Countries;

/**
 * Companies
 *
 * @ORM\Table(name="Companies", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Companies_Path_Slug_U", columns={"Path_Slug"})
 * }, indexes={
 *     @ORM\Index(name="Companies_ID_COUNTRIES_FK", columns={"ID_COUNTRIES"}),
 *     @ORM\Index(name="Companies_ID_CITIES_FK", columns={"ID_CITIES"}),
 *     @ORM\Index(name="Companies_ID_USERS_FK", columns={"ID_USERS"})
 * })
 * @ORM\Entity
 */
class Company
{
    /**
     * @var integer
     *
     * @Groups({"api"})
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
     * @Groups({"api"})
     *
     * @ORM\Column(name="Short_Name", type="string", length=50, nullable=false)
     */
    private $shortName;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(name="Full_Name", type="string", length=200, nullable=false)
     */
    private $fullName;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(name="First_Name", type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(name="Last_Name", type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(name="VAT", type="string", length=50, nullable=true)
     */
    private $vat;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(name="Postal_Code", type="string", length=20, nullable=false)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(name="Street", type="string", length=200, nullable=false)
     */
    private $street;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(name="Email", type="string", length=200, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @Groups({"api"})
     *
     * @ORM\Column(name="Phone", type="string", length=50, nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="Path_Slug", type="string", length=50, nullable=false)
     */
    private $pathSlug = '';

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CITIES", referencedColumnName="ID")
     * })
     */
    private $idCities;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_COUNTRIES", referencedColumnName="ID")
     * })
     */
    private $idCountries;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_USERS", referencedColumnName="ID")
     * })
     */
    private $idUsers;



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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * Set idCities
     *
     * @param City $idCities
     *
     * @return Company
     */
    public function setIdCities(City $idCities = null)
    {
        $this->idCities = $idCities;

        return $this;
    }

    /**
     * Get idCities
     *
     * @return City
     */
    public function getIdCities()
    {
        return $this->idCities;
    }

    /**
     * Set idCountries
     *
     * @param Country $idCountries
     *
     * @return Company
     */
    public function setIdCountries(Country $idCountries = null)
    {
        $this->idCountries = $idCountries;

        return $this;
    }

    /**
     * Get idCountries
     *
     * @return Country
     */
    public function getIdCountries()
    {
        return $this->idCountries;
    }

    /**
     * Set idUsers
     *
     * @param User $idUsers
     *
     * @return Company
     */
    public function setIdUsers(User $idUsers = null)
    {
        $this->idUsers = $idUsers;

        return $this;
    }

    /**
     * Get idUsers
     *
     * @return User
     */
    public function getIdUsers()
    {
        return $this->idUsers;
    }
}
