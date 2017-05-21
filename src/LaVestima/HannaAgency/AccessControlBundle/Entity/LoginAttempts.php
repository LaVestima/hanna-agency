<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;

/**
 * LoginAttempts
 *
 * @ORM\Table(name="Login_Attempts", indexes={@ORM\Index(name="Login_Attempts_ID_USERS_FK", columns={"ID_USERS"})})
 * @ORM\Entity
 */
class LoginAttempts
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
     * @ORM\Column(name="Time_Logged", type="datetime", nullable=false)
     */
    private $timeLogged = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="Ip_Adddress", type="string", length=50, nullable=false)
     */
    private $ipAdddress;

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
     * Set timeLogged
     *
     * @param \DateTime $timeLogged
     *
     * @return LoginAttempts
     */
    public function setTimeLogged($timeLogged)
    {
        $this->timeLogged = $timeLogged;

        return $this;
    }

    /**
     * Get timeLogged
     *
     * @return \DateTime
     */
    public function getTimeLogged()
    {
        return $this->timeLogged;
    }

    /**
     * Set ipAdddress
     *
     * @param string $ipAdddress
     *
     * @return LoginAttempts
     */
    public function setIpAdddress($ipAdddress)
    {
        $this->ipAdddress = $ipAdddress;

        return $this;
    }

    /**
     * Get ipAdddress
     *
     * @return string
     */
    public function getIpAdddress()
    {
        return $this->ipAdddress;
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
