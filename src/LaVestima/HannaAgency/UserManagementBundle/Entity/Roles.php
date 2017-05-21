<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Roles
 *
 * @ORM\Table(name="Roles", uniqueConstraints={@ORM\UniqueConstraint(name="Roles_Name_U", columns={"Name"}), @ORM\UniqueConstraint(name="Roles_Code_U", columns={"Code"})})
 * @ORM\Entity
 */
class Roles
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
    private $name = 'Guest';

    /**
     * @var string
     *
     * @ORM\Column(name="Code", type="string", length=50, nullable=false)
     */
    private $code = 'ROLE_GUEST';

    /**
     * @var boolean
     *
     * @ORM\Column(name="Is_Admin", type="boolean", nullable=false)
     */
    private $isAdmin = '0';



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
     * @return Roles
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
     * Set code
     *
     * @param string $code
     *
     * @return Roles
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     *
     * @return Roles
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }
}
