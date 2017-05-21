<?php

namespace LaVestima\HannaAgency\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Addresses
 *
 * @ORM\Table(name="Addresses", indexes={@ORM\Index(name="Addresses_ID_COUNTRIES_FK", columns={"ID_COUNTRIES"}), @ORM\Index(name="Addresses_ID_CITIES_FK", columns={"ID_CITIES"})})
 * @ORM\Entity
 */
class Addresses
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
     * @var \Cities
     *
     * @ORM\ManyToOne(targetEntity="Cities")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CITIES", referencedColumnName="ID")
     * })
     */
    private $idCities;

    /**
     * @var \Countries
     *
     * @ORM\ManyToOne(targetEntity="Countries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_COUNTRIES", referencedColumnName="ID")
     * })
     */
    private $idCountries;



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
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Addresses
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
     * @return Addresses
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
     * Set idCities
     *
     * @param \LaVestima\HannaAgency\LocationBundle\Entity\Cities $idCities
     *
     * @return Addresses
     */
    public function setIdCities(\LaVestima\HannaAgency\LocationBundle\Entity\Cities $idCities = null)
    {
        $this->idCities = $idCities;

        return $this;
    }

    /**
     * Get idCities
     *
     * @return \LaVestima\HannaAgency\LocationBundle\Entity\Cities
     */
    public function getIdCities()
    {
        return $this->idCities;
    }

    /**
     * Set idCountries
     *
     * @param \LaVestima\HannaAgency\LocationBundle\Entity\Countries $idCountries
     *
     * @return Addresses
     */
    public function setIdCountries(\LaVestima\HannaAgency\LocationBundle\Entity\Countries $idCountries = null)
    {
        $this->idCountries = $idCountries;

        return $this;
    }

    /**
     * Get idCountries
     *
     * @return \LaVestima\HannaAgency\LocationBundle\Entity\Countries
     */
    public function getIdCountries()
    {
        return $this->idCountries;
    }
}
