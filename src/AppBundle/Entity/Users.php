<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Users
 *
 * @ORM\Table(name="Users", uniqueConstraints={@ORM\UniqueConstraint(name="Users_LoginU", columns={"Login"}), @ORM\UniqueConstraint(name="Users_Password_HashU", columns={"Password_Hash"})})
 * @ORM\Entity
 */
class Users implements UserInterface, \Serializable {
    /**
     * @var string
     *
     * @ORM\Column(name="Login", type="string", length=50, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="Password_Hash", type="string", length=200, nullable=false)
     */
    private $passwordHash;

    /**
     * @var integer
     *
     * @ORM\Column(name="Is_Admin", type="integer", nullable=false)
     */
    private $isAdmin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_Created", type="datetime", nullable=false)
     */
    private $dateCreated = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

	public function __toString() {
		return $this->login;
	}

    /**
     * Set login
     *
     * @param string $login
     *
     * @return Users
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set passwordHash
     *
     * @param string $passwordHash
     *
     * @return Users
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }

    /**
     * Get passwordHash
     *
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * Set isAdmin
     *
     * @param integer $isAdmin
     *
     * @return Users
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return integer
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Users
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

	public function getUsername()
	{
		return $this->login;
	}

	public function getSalt()
	{
		return null;
	}

	public function getPassword()
	{
		return $this->passwordHash;
	}

	public function getRoles() {
		if ($this->isAdmin === 1) {
			return array('ROLE_ADMIN');
		}
		else {
			return array('ROLE_USER');
		}
	}

	public function eraseCredentials()
	{
	}

	/** @see \Serializable::serialize() */
	public function serialize()
	{
		return serialize(array(
			$this->id,
			$this->login,
			$this->passwordHash,
			// see section on salt below
			// $this->salt,
		));
	}

	/** @see \Serializable::unserialize() */
	public function unserialize($serialized)
	{
		list (
			$this->id,
			$this->login,
			$this->passwordHash,
			// see section on salt below
			// $this->salt
			) = unserialize($serialized);
	}
}
