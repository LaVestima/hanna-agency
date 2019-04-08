<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Customers
 *
 * @ORM\Table(name="Customers", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Customers_Identification_Number_U", columns={"Identification_Number"}),
 *     @ORM\UniqueConstraint(name="Customers_Path_Slug_U", columns={"Path_Slug"})
 * }, indexes={
 *     @ORM\Index(name="Customers_ID_USERS_FK", columns={"ID_USERS"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer implements EntityInterface
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

//    /**
//     * @var Country
//     *
//     * @ORM\ManyToOne(targetEntity="Country")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="ID_COUNTRIES", referencedColumnName="ID")
//     * })
//     */
//    private $idCountries;

//    /**
//     * @var City
//     *
//     * @ORM\ManyToOne(targetEntity="City")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="ID_CITIES", referencedColumnName="ID")
//     * })
//     */
//    private $idCities;

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

//    /**
//     * @var Currency
//     *
//     * @ORM\ManyToOne(targetEntity="Currency")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="ID_CURRENCIES", referencedColumnName="ID")
//     * })
//     */
//    private $idCurrencies;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_USERS")
     * })
     */
    private $idUsers;

    private $fullName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;


    public function __construct()
    {
        $this->dateCreated = new \DateTime();
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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

//    /**
//     * Set idCountries
//     *
//     * @param Country $idCountries
//     *
//     * @return $this
//     */
//    public function setIdCountries(Country $idCountries) {
//        $this->idCountries = $idCountries;
//
//        return $this;
//    }
//
//    /**
//     * Get idCountries
//     *
//     * @return Country
//     */
//    public function getIdCountries() {
//        return $this->idCountries;
//    }

//    /**
//     * Set idCities
//     *
//     * @param City $idCities
//     *
//     * @return $this
//     */
//    public function setIdCities(City $idCities) {
//        $this->idCities = $idCities;
//
//        return $this;
//    }
//
//    /**
//     * Get idCities
//     *
//     * @return City
//     */
//    public function getIdCities() {
//        return $this->idCities;
//    }

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

//    /**
//     * Set idCurrencies
//     *
//     * @param Currency $idCurrencies
//     *
//     * @return Customer
//     */
//    public function setIdCurrencies(Currency $idCurrencies)
//    {
//        $this->idCurrencies = $idCurrencies;
//
//        return $this;
//    }
//
//    /**
//     * Get idCurrencies
//     *
//     * @return Currency
//     */
//    public function getIdCurrencies()
//    {
//        return $this->idCurrencies;
//    }

    /**
     * Set idUsers
     *
     * @param User $idUsers
     *
     * @return Customer
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

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
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

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

//    public function jsonSerialize() // TODO: finish !!!!
//    {
//        $json = [];
//        $json['fullName'] = $this->fullName;
//        $json['identificationNumber'] = $this->identificationNumber;
//
//        return $json;
//    }
}
