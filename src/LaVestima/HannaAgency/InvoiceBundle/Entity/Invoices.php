<?php

namespace LaVestima\HannaAgency\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invoices
 *
 * @ORM\Table(name="Invoices", uniqueConstraints={@ORM\UniqueConstraint(name="Invoices_Name_U", columns={"Name"}), @ORM\UniqueConstraint(name="Invoices_Path_Slug_U", columns={"Path_Slug"})}, indexes={@ORM\Index(name="Invoices_ID_CUSTOMERS_FK", columns={"ID_CUSTOMERS"}), @ORM\Index(name="Invoices_ID_USERS_FK", columns={"ID_USERS"})})
 * @ORM\Entity
 */
class Invoices
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
     * @var integer
     *
     * @ORM\Column(name="User_Created", type="integer", nullable=false)
     */
    private $userCreated = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_Issued", type="datetime", nullable=false)
     */
    private $dateIssued = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="Path_Slug", type="string", length=50, nullable=false)
     */
    private $pathSlug = '';

    /**
     * @var \Customers
     *
     * @ORM\ManyToOne(targetEntity="Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CUSTOMERS", referencedColumnName="ID")
     * })
     */
    private $idCustomers;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
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
     * @return Invoices
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
     * Set userCreated
     *
     * @param integer $userCreated
     *
     * @return Invoices
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
     * Set name
     *
     * @param string $name
     *
     * @return Invoices
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
     * Set dateIssued
     *
     * @param \DateTime $dateIssued
     *
     * @return Invoices
     */
    public function setDateIssued($dateIssued)
    {
        $this->dateIssued = $dateIssued;

        return $this;
    }

    /**
     * Get dateIssued
     *
     * @return \DateTime
     */
    public function getDateIssued()
    {
        return $this->dateIssued;
    }

    /**
     * Set pathSlug
     *
     * @param string $pathSlug
     *
     * @return Invoices
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
     * Set idCustomers
     *
     * @param \LaVestima\HannaAgency\InvoiceBundle\Entity\Customers $idCustomers
     *
     * @return Invoices
     */
    public function setIdCustomers(\LaVestima\HannaAgency\InvoiceBundle\Entity\Customers $idCustomers = null)
    {
        $this->idCustomers = $idCustomers;

        return $this;
    }

    /**
     * Get idCustomers
     *
     * @return \LaVestima\HannaAgency\InvoiceBundle\Entity\Customers
     */
    public function getIdCustomers()
    {
        return $this->idCustomers;
    }

    /**
     * Set idUsers
     *
     * @param \LaVestima\HannaAgency\InvoiceBundle\Entity\Users $idUsers
     *
     * @return Invoices
     */
    public function setIdUsers(\LaVestima\HannaAgency\InvoiceBundle\Entity\Users $idUsers = null)
    {
        $this->idUsers = $idUsers;

        return $this;
    }

    /**
     * Get idUsers
     *
     * @return \LaVestima\HannaAgency\InvoiceBundle\Entity\Users
     */
    public function getIdUsers()
    {
        return $this->idUsers;
    }
}
