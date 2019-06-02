<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="order_2983", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Orders_Path_Slug_U", columns={"path_slug"})
 * }, indexes={
 *     @ORM\Index(name="Orders_User_Created_FK", columns={"user_created"}),
 *     @ORM\Index(name="Orders_User_Deleted_FK", columns={"user_deleted"}),
 *     @ORM\Index(name="Orders_User_FK", columns={"user_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order implements EntityInterface
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDeleted;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $pathSlug = '';

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_created")
     * })
     */
    private $userCreated;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_deleted")
     * })
     */
    private $userDeleted;

    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="OrderProductVariant", mappedBy="order", orphanRemoval=true, fetch="EAGER", cascade={"persist"})
     */
    private $orderProductVariants;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $code;

    public function __construct()
    {
        $this->orderProductVariants = new ArrayCollection();
    }


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
        // TODO: finish status determination

        foreach ($this->orderProductVariants as $orderProductVariant) {
            if ($orderProductVariant->getStatus()->getName() == OrderStatus::REJECTED) {
                $this->status = OrderStatus::REJECTED;

                break;
            }


        }




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
    public function getOrderProductVariants()
    {
        return $this->orderProductVariants;
    }

    /**
     * @param mixed $orderProductVariants
     */
    public function setOrderProductVariants($orderProductVariants): void
    {
        $this->orderProductVariants = $orderProductVariants;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function addOrderProduct(OrderProductVariant $orderProduct): self
    {
        if (!$this->orderProductVariants->contains($orderProduct)) {
            $this->orderProductVariants[] = $orderProduct;
            $orderProduct->setOrder1($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProductVariant $orderProduct): self
    {
        if ($this->orderProductVariants->contains($orderProduct)) {
            $this->orderProductVariants->removeElement($orderProduct);
            // set the owning side to null (unless already changed)
            if ($orderProduct->getOrder1() === $this) {
                $orderProduct->setOrder1(null);
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
