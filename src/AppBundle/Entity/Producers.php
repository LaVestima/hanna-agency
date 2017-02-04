<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Producers
 *
 * @ORM\Table(name="Producers", indexes={@ORM\Index(name="FK_160DB71E741523F5", columns={"ID_CITIES"}), @ORM\Index(name="FK_160DB71E73C1D4A", columns={"ID_COUNTRIES"}), @ORM\Index(name="FK_160DB71E3B997DA3", columns={"ID_USERS"})})
 * @ORM\Entity
 */
class Producers
{
    /**
     * @var string
     *
     * @ORM\Column(name="Short_Name", type="string", length=50, nullable=false)
     */
    private $shortName;

    /**
     * @var string
     *
     * @ORM\Column(name="Full_Name", type="string", length=200, nullable=false)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="First_Name", type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="Last_Name", type="string", length=50, nullable=true)
     */
    private $lastName;

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
     * Set shortName
     *
     * @param string $shortName
     *
     * @return Producers
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
     * @return Producers
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
     * @return Producers
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
     * @return Producers
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
     * @return Producers
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
     * @return Producers
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
     * @return Producers
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
     * @return Producers
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
     * @return Producers
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
     * @return Producers
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
     * Set idCountries
     *
     * @param \AppBundle\Entity\Countries $idCountries
     *
     * @return Producers
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
     * @return Producers
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
}
