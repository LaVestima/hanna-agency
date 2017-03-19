<?php

namespace LaVestima\HannaAgency\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrdersStatuses
 *
 * @ORM\Table(name="Orders_Statuses", uniqueConstraints={@ORM\UniqueConstraint(name="Orders_Statuses_Name_U", columns={"Name"})})
 * @ORM\Entity
 */
class OrdersStatuses
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
     * @ORM\Column(name="Name", type="string", length=50, nullable=false)
     */
    private $name;



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
     * @return OrdersStatuses
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
}
