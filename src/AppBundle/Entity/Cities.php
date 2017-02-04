<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cities
 *
 * @ORM\Table(name="Cities", uniqueConstraints={@ORM\UniqueConstraint(name="Cities_Name_CountriesU", columns={"Name", "ID_COUNTRIES"})}, indexes={@ORM\Index(name="FK_DEF1B45D73C1D4A", columns={"ID_COUNTRIES"})})
 * @ORM\Entity
 */
class Cities
{
    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Note", type="string", length=200, nullable=true)
     */
    private $note;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Countries
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Countries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_COUNTRIES", referencedColumnName="ID")
     * })
     */
    private $idCountries;

    public function __toString() {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Cities
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
     * Set note
     *
     * @param string $note
     *
     * @return Cities
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
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
     * Set idCountries
     *
     * @param \AppBundle\Entity\Countries $idCountries
     *
     * @return Cities
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
}
