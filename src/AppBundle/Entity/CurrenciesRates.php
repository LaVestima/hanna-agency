<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CurrenciesRates
 *
 * @ORM\Table(name="Currencies_Rates", uniqueConstraints={@ORM\UniqueConstraint(name="Currencies_H_Date_CurrencyU", columns={"Date_Rate", "ID_CURRENCIES"})}, indexes={@ORM\Index(name="FK_8C585099329E4B76", columns={"ID_CURRENCIES"})})
 * @ORM\Entity
 */
class CurrenciesRates
{
    /**
     * @var string
     *
     * @ORM\Column(name="Conversion_Rate", type="decimal", precision=10, scale=6, nullable=false)
     */
    private $conversionRate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_Rate", type="datetime", nullable=false)
     */
    private $dateRate = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * Set conversionRate
     *
     * @param string $conversionRate
     *
     * @return CurrenciesRates
     */
    public function setConversionRate($conversionRate)
    {
        $this->conversionRate = $conversionRate;

        return $this;
    }

    /**
     * Get conversionRate
     *
     * @return string
     */
    public function getConversionRate()
    {
        return $this->conversionRate;
    }

    /**
     * Set dateRate
     *
     * @param \DateTime $dateRate
     *
     * @return CurrenciesRates
     */
    public function setDateRate($dateRate)
    {
        $this->dateRate = $dateRate;

        return $this;
    }

    /**
     * Get dateRate
     *
     * @return \DateTime
     */
    public function getDateRate()
    {
        return $this->dateRate;
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
     * Set idCurrencies
     *
     * @param \AppBundle\Entity\Currencies $idCurrencies
     *
     * @return CurrenciesRates
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
}
