<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customers
 *
 * @ORM\Table(name="Customers", uniqueConstraints={@ORM\UniqueConstraint(name="Customers_Identification_NumberU", columns={"Identification_Number"})}, indexes={@ORM\Index(name="Customers_ID_COUNTRIES_FK", columns={"ID_COUNTRIES"}), @ORM\Index(name="Customers_ID_CITIES_FK", columns={"ID_CITIES"}), @ORM\Index(name="Customers_ID_CURRENCIES_FK", columns={"ID_CURRENCIES"}), @ORM\Index(name="Customers_ID_USERS_FK", columns={"ID_USERS"})})
 * @ORM\Entity
 */
class Customers
{
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
     * @ORM\Column(name="Street", type="string", length=200, nullable=false)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="Postal_Code", type="string", length=20, nullable=false)
     */
    private $postalCode;

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
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_USERS", referencedColumnName="ID")
     * })
     */
    private $idUsers;

    /**
     * @var \AppBundle\Entity\Currencies
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Currencies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CURRENCIES", referencedColumnName="ID")
     * })
     */
    private $idCurrencies;

    /**
     * @var \AppBundle\Entity\Countries
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Countries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_COUNTRIES", referencedColumnName="ID")
     * })
     */
    private $idCountries;

    /**
     * @var \AppBundle\Entity\Cities
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cities")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CITIES", referencedColumnName="ID")
     * })
     */
    private $idCities;



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
     * Set street
     *
     * @param string $street
     *
     * @return Customers
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
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Customers
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
    public function getDefaultDiscount()
    {
        return $this->defaultDiscount;
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
     * Set idUsers
     *
     * @param \AppBundle\Entity\Users $idUsers
     *
     * @return Customers
     */
    public function setIdUsers(\AppBundle\Entity\Users $idUsers = null)
    {
        $this->idUsers = $idUsers;

        return $this;
    }

    /**
     * Get idUsers
     *
     * @return \AppBundle\Entity\Users
     */
    public function getIdUsers()
    {
        return $this->idUsers;
    }

    /**
     * Set idCurrencies
     *
     * @param \AppBundle\Entity\Currencies $idCurrencies
     *
     * @return Customers
     */
    public function setIdCurrencies(\AppBundle\Entity\Currencies $idCurrencies = null)
    {
        $this->idCurrencies = $idCurrencies;

        return $this;
    }

    /**
     * Get idCurrencies
     *
     * @return \AppBundle\Entity\Currencies
     */
    public function getIdCurrencies()
    {
        return $this->idCurrencies;
    }

    /**
     * Set idCountries
     *
     * @param \AppBundle\Entity\Countries $idCountries
     *
     * @return Customers
     */
    public function setIdCountries(\AppBundle\Entity\Countries $idCountries = null)
    {
        $this->idCountries = $idCountries;

        return $this;
    }

    /**
     * Get idCountries
     *
     * @return \AppBundle\Entity\Countries
     */
    public function getIdCountries()
    {
        return $this->idCountries;
    }

    /**
     * Set idCities
     *
     * @param \AppBundle\Entity\Cities $idCities
     *
     * @return Customers
     */
    public function setIdCities(\AppBundle\Entity\Cities $idCities = null)
    {
        $this->idCities = $idCities;

        return $this;
    }

    /**
     * Get idCities
     *
     * @return \AppBundle\Entity\Cities
     */
    public function getIdCities()
    {
        return $this->idCities;
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
}
