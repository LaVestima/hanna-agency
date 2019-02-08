<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cities
 *
 * @ORM\Table(name="Cities", uniqueConstraints={@ORM\UniqueConstraint(name="Cities_Name_Countries_U", columns={"Name", "ID_COUNTRIES"})}, indexes={@ORM\Index(name="Cities_ID_COUNTRIES_FK", columns={"ID_COUNTRIES"})})
 * @ORM\Entity
 */
class City
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
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
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
     * Set name
     *
     * @param string $name
     *
     * @return City
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
     * @return City
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
     * Set idCountries
     *
     * @param Country $idCountries
     *
     * @return City
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
}