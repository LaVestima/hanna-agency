<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="Orders", uniqueConstraints={@ORM\UniqueConstraint(name="Orders_Path_SlugU", columns={"Path_Slug"})}, indexes={@ORM\Index(name="FK_E283F8D83809B8C6", columns={"ID_CUSTOMERS"}), @ORM\Index(name="FK_E283F8D83B997DA3", columns={"ID_USERS"})})
 * @ORM\Entity
 */
class Orders
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_Placed", type="datetime", nullable=false)
     */
    private $datePlaced = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="Path_Slug", type="string", length=50, nullable=false)
     */
    private $pathSlug;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Customers
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CUSTOMERS", referencedColumnName="ID")
     * })
     */
    private $idCustomers;

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
     * Set datePlaced
     *
     * @param \DateTime $datePlaced
     *
     * @return Orders
     */
    public function setDatePlaced($datePlaced)
    {
        $this->datePlaced = $datePlaced;

        return $this;
    }

    /**
     * Get datePlaced
     *
     * @return \DateTime
     */
    public function getDatePlaced()
    {
        return $this->datePlaced;
    }

    /**
     * Set pathSlug
     *
     * @param string $pathSlug
     *
     * @return Orders
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idCustomers
     *
     * @param \AppBundle\Entity\Customers $idCustomers
     *
     * @return Orders
     */
    public function setIdCustomers(\AppBundle\Entity\Customers $idCustomers = null)
    {
        $this->idCustomers = $idCustomers;

        return $this;
    }

    /**
     * Get idCustomers
     *
     * @return \AppBundle\Entity\Customers
     */
    public function getIdCustomers()
    {
        return $this->idCustomers;
    }

    /**
     * Set idUsers
     *
     * @param \AppBundle\Entity\Users $idUsers
     *
     * @return Orders
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
}
