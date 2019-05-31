<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Orders_Statuses_Name_U", columns={"name"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\OrderStatusRepository")
 */
class OrderStatus implements EntityInterface
{
    const QUEUED = 'Queued';
    const PENDING = 'Pending';
    const COMPLETED = 'Completed';
    const REJECTED = 'Rejected';

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
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProductVariant", mappedBy="status", orphanRemoval=true)
     */
    private $orderProductVariants;

    public function __construct()
    {
        $this->orderProductVariants = new ArrayCollection();
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
     * @return OrderStatus
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
     * @return Collection|OrderProductVariant[]
     */
    public function getOrderProductVariants(): Collection
    {
        return $this->orderProductVariants;
    }

    public function addOrderProductVariant(OrderProductVariant $orderProductVariant): self
    {
        if (!$this->orderProductVariants->contains($orderProductVariant)) {
            $this->orderProductVariants[] = $orderProductVariant;
            $orderProductVariant->setStatus($this);
        }

        return $this;
    }

    public function removeOrderProductVariant(OrderProductVariant $orderProductVariant): self
    {
        if ($this->orderProductVariants->contains($orderProductVariant)) {
            $this->orderProductVariants->removeElement($orderProductVariant);
            // set the owning side to null (unless already changed)
            if ($orderProductVariant->getStatus() === $this) {
                $orderProductVariant->setStatus(null);
            }
        }

        return $this;
    }
}
