<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Countries_Name_U", columns={"Name"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country implements EntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="country", orphanRemoval=true)
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\City", mappedBy="country", orphanRemoval=true)
     */
    private $cities;

    /**
     * @ORM\OneToMany(targetEntity="Store", mappedBy="country", orphanRemoval=true)
     */
    private $producers;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $code;


    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->cities = new ArrayCollection();
        $this->producers = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Country
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
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setCountry($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getCountry() === $this) {
                $address->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|City[]
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities[] = $city;
            $city->setCountry($this);
        }

        return $this;
    }

    public function removeCity(City $city): self
    {
        if ($this->cities->contains($city)) {
            $this->cities->removeElement($city);
            // set the owning side to null (unless already changed)
            if ($city->getCountry() === $this) {
                $city->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Store[]
     */
    public function getProducers(): Collection
    {
        return $this->producers;
    }

    public function addProducer(Store $producer): self
    {
        if (!$this->producers->contains($producer)) {
            $this->producers[] = $producer;
            $producer->setCountry($this);
        }

        return $this;
    }

    public function removeProducer(Store $producer): self
    {
        if ($this->producers->contains($producer)) {
            $this->producers->removeElement($producer);
            // set the owning side to null (unless already changed)
            if ($producer->getCountry() === $this) {
                $producer->setCountry(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
