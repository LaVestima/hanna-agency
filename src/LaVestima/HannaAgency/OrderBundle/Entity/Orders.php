<?php

namespace LaVestima\HannaAgency\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LaVestima\HannaAgency\CustomerBundle\Entity\Customers;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;

/**
 * Orders
 *
 * @ORM\Table(name="Orders", uniqueConstraints={@ORM\UniqueConstraint(name="Orders_Path_Slug_U", columns={"Path_Slug"})}, indexes={@ORM\Index(name="Orders_User_Created_FK", columns={"User_Created"}), @ORM\Index(name="Orders_User_Deleted_FK", columns={"User_Deleted"}), @ORM\Index(name="Orders_ID_CUSTOMERS_FK", columns={"ID_CUSTOMERS"})})
 * @ORM\Entity
 */
class Orders implements EntityInterface, \JsonSerializable
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
     * @var \DateTime
     *
     * @ORM\Column(name="Date_Deleted", type="datetime", nullable=true)
     */
    private $dateDeleted;

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
    private $pathSlug = '';

    /**
     * @var Customers
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\CustomerBundle\Entity\Customers", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CUSTOMERS", referencedColumnName="ID")
     * })
     */
    private $idCustomers;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\UserManagementBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="User_Created", referencedColumnName="ID")
     * })
     */
    private $userCreated;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\UserManagementBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="User_Deleted", referencedColumnName="ID")
     * })
     */
    private $userDeleted;

    private $status;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Orders
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * Set dateDeleted
     *
     * @param \DateTime $dateDeleted
     *
     * @return Orders
     */
    public function setDateDeleted($dateDeleted) {
        $this->dateDeleted = $dateDeleted;

        return $this;
    }

    /**
     * Get dateDeleted
     *
     * @return \DateTime
     */
    public function getDateDeleted() {
        return $this->dateDeleted;
    }

    /**
     * Set datePlaced
     *
     * @param \DateTime $datePlaced
     *
     * @return Orders
     */
    public function setDatePlaced($datePlaced) {
        $this->datePlaced = $datePlaced;

        return $this;
    }

    /**
     * Get datePlaced
     *
     * @return \DateTime
     */
    public function getDatePlaced() {
        return $this->datePlaced;
    }

    /**
     * Set pathSlug
     *
     * @param string $pathSlug
     *
     * @return Orders
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

    /**
     * Set idCustomers
     *
     * @param Customers $idCustomers
     *
     * @return Orders
     */
    public function setIdCustomers(Customers $idCustomers = null) {
        $this->idCustomers = $idCustomers;

        return $this;
    }

    /**
     * Get idCustomers
     *
     * @return Customers
     */
    public function getIdCustomers() {
        return $this->idCustomers;
    }

    /**
     * Set userCreated
     *
     * @param Users $userCreated
     *
     * @return Orders
     */
    public function setUserCreated(Users $userCreated = null) {
        $this->userCreated = $userCreated;

        return $this;
    }

    /**
     * Get userCreated
     *
     * @return Users
     */
    public function getUserCreated() {
        return $this->userCreated;
    }

    /**
     * Set userDeleted
     *
     * @param Users $userDeleted
     *
     * @return Orders
     */
    public function setUserDeleted(Users $userDeleted = null) {
        $this->userDeleted = $userDeleted;

        return $this;
    }

    /**
     * Get userDeleted
     *
     * @return Users
     */
    public function getUserDeleted() {
        return $this->userDeleted;
    }

    public function setStatus(OrdersStatuses $status) {
        $this->status = $status;

        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function jsonSerialize() // TODO: finish !!!
    {
        $json = [];
        $json['datePlaced'] = $this->datePlaced->format('d.m.Y');
        $json['status'] = $this->status;
        $json['customer'] = $this->idCustomers->getFullName();


        return $json;
    }
}
