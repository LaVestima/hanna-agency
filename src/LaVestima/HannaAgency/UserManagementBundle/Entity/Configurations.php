<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Configurations
 *
 * @ORM\Table(name="Configurations", uniqueConstraints={@ORM\UniqueConstraint(name="Configurations_ID_USERS_U", columns={"ID_USERS"})})
 * @ORM\Entity
 */
class Configurations
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
     * @ORM\Column(name="Configuration", type="string", length=50, nullable=false)
     */
    private $configuration;

    /**
     * @var Users
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
     * Set configuration
     *
     * @param string $configuration
     *
     * @return Configurations
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Get configuration
     *
     * @return string
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Set idUsers
     *
     * @param Users $idUsers
     *
     * @return Configurations
     */
    public function setIdUsers(Users $idUsers = null)
    {
        $this->idUsers = $idUsers;

        return $this;
    }

    /**
     * Get idUsers
     *
     * @return Users
     */
    public function getIdUsers()
    {
        return $this->idUsers;
    }
}
