<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;

/**
 * LoginAttempts
 *
 * @ORM\Table(name="Login_Attempts", indexes={@ORM\Index(name="Login_Attempts_ID_USERS_FK", columns={"ID_USERS"})})
 * @ORM\Entity
 */
class LoginAttempts implements EntityInterface
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
     * @var string
     *
     * @ORM\Column(name="Ip_Address", type="string", length=50, nullable=false)
     */
    private $ipAddress;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Is_Failed", type="boolean", nullable=false)
     */
    private $isFailed;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="LaVestima\HannaAgency\UserManagementBundle\Entity\Users")
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
     * @return LoginAttempts
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
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return LoginAttempts
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set isFailed
     *
     * @param boolean $isFailed
     *
     * @return LoginAttempts
     */
    public function setIsFailed($isFailed)
    {
        $this->isFailed = $isFailed;

        return $this;
    }

    /**
     * Get isFailed
     *
     * @return boolean
     */
    public function getIsFailed()
    {
        return $this->isFailed;
    }

    /**
     * Set idUsers
     *
     * @param Users $idUsers
     *
     * @return LoginAttempts
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
