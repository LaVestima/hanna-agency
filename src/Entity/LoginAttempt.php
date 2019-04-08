<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * LoginAttempts
 *
 * @ORM\Table(indexes={
 *     @ORM\Index(name="Login_Attempt_User_FK", columns={"user_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\LoginAttemptRepository")
 */
class LoginAttempt implements EntityInterface
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
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $ipAddress;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isFailed;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="loginAttempts")
     */
    private $user;


    public function __construct()
    {
        $this->dateCreated = new \DateTime();
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
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return LoginAttempt
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
     * @return LoginAttempt
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
     * @return LoginAttempt
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
