<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tokens
 *
 * @ORM\Table(name="Tokens", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Tokens_Token_U", columns={"Token"})
 * }, indexes={
 *     @ORM\Index(name="Tokens_ID_USERS_FK", columns={"ID_USERS"})
 * })
 * @ORM\Entity
 */
class Token implements EntityInterface
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
     * @ORM\Column(name="Date_Expired", type="datetime", nullable=false)
     */
    private $dateExpired = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="Token", type="string", length=100, nullable=false)
     */
    private $token;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
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
     * @return Token
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
     * Set dateExpired
     *
     * @param \DateTime $dateExpired
     *
     * @return Token
     */
    public function setDateExpired($dateExpired)
    {
        $this->dateExpired = $dateExpired;

        return $this;
    }

    /**
     * Get dateExpired
     *
     * @return \DateTime
     */
    public function getDateExpired()
    {
        return $this->dateExpired;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Token
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set idUsers
     *
     * @param User $idUsers
     *
     * @return Token
     */
    public function setIdUsers(User $idUsers = null)
    {
        $this->idUsers = $idUsers;

        return $this;
    }

    /**
     * Get idUsers
     *
     * @return User
     */
    public function getIdUsers()
    {
        return $this->idUsers;
    }
}
