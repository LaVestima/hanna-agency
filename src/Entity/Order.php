<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="Orders", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Orders_Path_Slug_U", columns={"Path_Slug"})
 * }, indexes={
 *     @ORM\Index(name="Orders_User_Created_FK", columns={"User_Created"}),
 *     @ORM\Index(name="Orders_User_Deleted_FK", columns={"User_Deleted"}),
 *     @ORM\Index(name="Orders_ID_CUSTOMERS_FK", columns={"ID_CUSTOMERS"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order implements EntityInterface
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
     * @var string
     *
     * @ORM\Column(name="Path_Slug", type="string", length=50, nullable=false)
     */
    private $pathSlug = '';

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CUSTOMERS", referencedColumnName="ID")
     * })
     */
    private $idCustomers;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="User_Created", referencedColumnName="ID")
     * })
     */
    private $userCreated;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="User_Deleted", referencedColumnName="ID")
     * })
     */
    private $userDeleted;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="idOrders")
     */
    private $orderProducts;

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
     * @return Order
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
     * @return Order
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
     * Set pathSlug
     *
     * @param string $pathSlug
     *
     * @return Order
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
     * @param Customer $idCustomers
     *
     * @return Order
     */
    public function setIdCustomers(Customer $idCustomers = null) {
        $this->idCustomers = $idCustomers;

        return $this;
    }

    /**
     * Get idCustomers
     *
     * @return Customer
     */
    public function getIdCustomers() {
        return $this->idCustomers;
    }

    /**
     * Set userCreated
     *
     * @param User $userCreated
     *
     * @return Order
     */
    public function setUserCreated(User $userCreated = null) {
        $this->userCreated = $userCreated;

        return $this;
    }

    /**
     * Get userCreated
     *
     * @return User
     */
    public function getUserCreated() {
        return $this->userCreated;
    }

    /**
     * Set userDeleted
     *
     * @param User $userDeleted
     *
     * @return Order
     */
    public function setUserDeleted(User $userDeleted = null) {
        $this->userDeleted = $userDeleted;

        return $this;
    }

    /**
     * Get userDeleted
     *
     * @return User
     */
    public function getUserDeleted() {
        return $this->userDeleted;
    }

    public function setStatus(OrderStatus $status) {
        $this->status = $status;

        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

//    public function jsonSerialize() // TODO: finish !!!
//    {
//        $json = [];
//        $json['status'] = $this->status;
//        $json['customer'] = $this->idCustomers->getFullName();
//
//
//        return $json;
//    }

    /**
     * @return mixed
     */
    public function getOrderProducts()
    {
        return $this->orderProducts;
    }

    /**
     * @param mixed $orderProducts
     */
    public function setOrderProducts($orderProducts): void
    {
        $this->orderProducts = $orderProducts;
    }

    static public function getStatusColumnQuery()
    {
        return '
            (CASE
                WHEN EXISTS(
                    SELECT os1.name
                    FROM LaVestima\HannaAgency\OrderBundle\Entity\OrdersProducts op1
                    INNER JOIN LaVestima\HannaAgency\OrderBundle\Entity\OrdersStatuses os1 WITH op1.idStatuses=os1.id
                    WHERE os1.name=\'Rejected\'
                    AND o.id=op1.idOrders
                ) THEN \'Rejected\'
                WHEN EXISTS(
                    SELECT os2.name
                    FROM LaVestima\HannaAgency\OrderBundle\Entity\OrdersProducts op2
                    INNER JOIN LaVestima\HannaAgency\OrderBundle\Entity\OrdersStatuses os2 WITH op2.idStatuses=os2.id
                    WHERE os2.name=\'Pending\'
                    AND o.id=op2.idOrders
                ) THEN \'Pending\'
                WHEN EXISTS(
                    SELECT os3.name
                    FROM LaVestima\HannaAgency\OrderBundle\Entity\OrdersProducts op3
                    INNER JOIN LaVestima\HannaAgency\OrderBundle\Entity\OrdersStatuses os3 WITH op3.idStatuses=os3.id
                    WHERE os3.name=\'Queued\'
                    AND o.id=op3.idOrders
                ) THEN \'Queued\'
                WHEN \'Completed\'=ANY(
                    SELECT os4.name
                    FROM LaVestima\HannaAgency\OrderBundle\Entity\OrdersProducts op4
                    INNER JOIN LaVestima\HannaAgency\OrderBundle\Entity\OrdersStatuses os4 WITH op4.idStatuses=os4.id
                    WHERE os4.name=\'Completed\'
                    AND o.id=op4.idOrders
                ) THEN \'Completed\'
                ELSE \'\'
            END) AS status
         ';
    }
}
