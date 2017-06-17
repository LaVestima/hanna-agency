<?php

namespace LaVestima\HannaAgency\CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LaVestima\HannaAgency\LocationBundle\Entity\Addresses;
use LaVestima\HannaAgency\LocationBundle\Entity\Cities;
use LaVestima\HannaAgency\LocationBundle\Entity\Countries;
use LaVestima\HannaAgency\MoneyBundle\Entity\Currencies;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;

/**
 * Customers
 *
 * @ORM\Table(name="Customers", uniqueConstraints={@ORM\UniqueConstraint(name="Customers_Identification_Number_U", columns={"Identification_Number"}), @ORM\UniqueConstraint(name="Customers_Path_Slug_U", columns={"Path_Slug"})}, indexes={@ORM\Index(name="Customers_ID_ADDRESSES_FK", columns={"ID_ADDRESSES"}), @ORM\Index(name="Customers_ID_CURRENCIES_FK", columns={"ID_CURRENCIES"}), @ORM\Index(name="Customers_ID_USERS_FK", columns={"ID_USERS"})})
 * @ORM\Entity
 */
class Customers implements \JsonSerializable
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
     * @ORM\Column(name="Identification_Number", type="string", length=10, nullable=false)
     */
    private $identificationNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="First_Name", type="string", length=50, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="Last_Name", type="string", length=50, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="Gender", type="string", length=1, nullable=false)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="Company_Name", type="string", length=200, nullable=true)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="VAT", type="string", length=50, nullable=true)
     */
    private $vat;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=200, nullable=false)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Newsletter", type="boolean", nullable=false)
     */
    private $newsletter = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="Phone", type="string", length=50, nullable=false)
     */
    private $phone;

    /**
     * @var integer
     *
     * @ORM\Column(name="Default_Discount", type="integer", nullable=true)
     */
    private $defaultDiscount = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="Path_Slug", type="string", length=50, nullable=false)
     */
    private $pathSlug = '';

    /**
     * @var Countries
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\LocationBundle\Entity\Countries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_COUNTRIES", referencedColumnName="ID")
     * })
     */
    private $idCountries;

    /**
     * @var Cities
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\LocationBundle\Entity\Cities")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CITIES", referencedColumnName="ID")
     * })
     */
    private $idCities;

    /**
     * @var string
     *
     * @ORM\Column(name="Postal_Code", type="string", length=20, nullable=false)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="Street", type="string", length=200, nullable=false)
     */
    private $street;

    /**
     * @var Currencies
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\MoneyBundle\Entity\Currencies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CURRENCIES", referencedColumnName="ID")
     * })
     */
    private $idCurrencies;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\UserManagementBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_USERS", referencedColumnName="ID")
     * })
     */
    private $idUsers;

    private $fullName;


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
     * @return Customers
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
     * @return Customers
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
     * @return Customers
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
     * @return Customers
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
     * Set identificationNumber
     *
     * @param string $identificationNumber
     *
     * @return Customers
     */
    public function setIdentificationNumber($identificationNumber)
    {
        $this->identificationNumber = $identificationNumber;

        return $this;
    }

    /**
     * Get identificationNumber
     *
     * @return string
     */
    public function getIdentificationNumber()
    {
        return $this->identificationNumber;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Customers
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
     * @return Customers
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
     * Set gender
     *
     * @param string $gender
     *
     * @return Customers
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Get full gender name
     *
     * @return string
     */
    public function getFullGender() {
        return $this->gender == 'M' ? 'Male' :
            ($this->gender == 'F' ? 'Female' : 'Other');
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Customers
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set vat
     *
     * @param string $vat
     *
     * @return Customers
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
     * Set email
     *
     * @param string $email
     *
     * @return Customers
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
     * Set newsletter
     *
     * @param boolean $newsletter
     *
     * @return Customers
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return boolean
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Customers
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
     * Set defaultDiscount
     *
     * @param integer $defaultDiscount
     *
     * @return Customers
     */
    public function setDefaultDiscount($defaultDiscount)
    {
        $this->defaultDiscount = $defaultDiscount;

        return $this;
    }

    /**
     * Get defaultDiscount
     *
     * @return integer
     */
    public function getDefaultDiscount() {
        return $this->defaultDiscount;
    }

    /**
     * Set pathSlug
     *
     * @param string $pathSlug
     *
     * @return Customers
     */
    public function setPathSlug($pathSlug) {
        $this->pathSlug = $pathSlug;

        return $this;
    }

    /**
     * Get pathSlug
     *
     * @return string
     */
    public function getPathSlug() {
        return $this->pathSlug;
    }

    /**
     * Set idCountries
     *
     * @param Countries $idCountries
     *
     * @return $this
     */
    public function setIdCountries(Countries $idCountries) {
        $this->idCountries = $idCountries;

        return $this;
    }

    /**
     * Get idCountries
     *
     * @return Countries
     */
    public function getIdCountries() {
        return $this->idCountries;
    }

    /**
     * Set idCities
     *
     * @param Cities $idCities
     *
     * @return $this
     */
    public function setIdCities(Cities $idCities) {
        $this->idCities = $idCities;

        return $this;
    }

    /**
     * Get idCities
     *
     * @return Cities
     */
    public function getIdCities() {
        return $this->idCities;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return $this
     */
    public function setPostalCode(string $postalCode) {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode() {
        return $this->postalCode;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return $this
     */
    public function setStreet(string $street) {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * Set idCurrencies
     *
     * @param Currencies $idCurrencies
     *
     * @return Customers
     */
    public function setIdCurrencies(Currencies $idCurrencies = null)
    {
        $this->idCurrencies = $idCurrencies;

        return $this;
    }

    /**
     * Get idCurrencies
     *
     * @return Currencies
     */
    public function getIdCurrencies()
    {
        return $this->idCurrencies;
    }

    /**
     * Set idUsers
     *
     * @param Users $idUsers
     *
     * @return Customers
     */
    public function setIdUsers(Users $idUsers = null)
    {
        $this->idUsers = $idUsers;

        return $this;
    }

    /**
     * Get idUsers
     *
     * @return Users
     */
    public function getIdUsers()
    {
        return $this->idUsers;
    }
    
    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function jsonSerialize() // TODO: finish !!!!
    {
        $json = [];
        $json['fullName'] = $this->fullName;
        $json['identificationNumber'] = $this->identificationNumber;

        return $json;
    }
}
