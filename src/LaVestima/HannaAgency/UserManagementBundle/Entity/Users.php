<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Users
 *
 * @ORM\Table(name="Users", uniqueConstraints={@ORM\UniqueConstraint(name="Users_Login_U", columns={"Login"}), @ORM\UniqueConstraint(name="Users_Email_U", columns={"Email"}), @ORM\UniqueConstraint(name="Users_Password_Hash_U", columns={"Password_Hash"})}, indexes={@ORM\Index(name="Users_ID_ROLES_FK", columns={"ID_ROLES"})})
 * @ORM\Entity
 */
class Users implements UserInterface, \Serializable
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
     * @ORM\Column(name="Date_Deleted", type="datetime", nullable=true)
     */
    private $dateDeleted;

    /**
     * @var string
     *
     * @ORM\Column(name="Login", type="string", length=50, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="Password_Hash", type="string", length=200, nullable=false)
     */
    private $passwordHash;

    /**
     * @var Roles
     *
     * @ORM\ManyToOne(targetEntity="Roles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_ROLES", referencedColumnName="ID")
     * })
     */
    private $idRoles;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="Path_Slug", type="string", length=50, nullable=false)
	 */
	private $pathSlug = '';



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
     * Set dateDeleted
     *
     * @param \DateTime $dateDeleted
     *
     * @return Users
     */
    public function setDateDeleted($dateDeleted)
    {
        $this->dateDeleted = $dateDeleted;

        return $this;
    }

    /**
     * Get dateDeleted
     *
     * @return \DateTime
     */
    public function getDateDeleted()
    {
        return $this->dateDeleted;
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
     * Set email
     *
     * @param string $email
     *
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * Set idRoles
     *
     * @param Roles $idRoles
     *
     * @return Users
     */
    public function setIdRoles(Roles $idRoles = null)
    {
        $this->idRoles = $idRoles;

        return $this;
    }

    /**
     * Get idRoles
     *
     * @return Roles
     */
    public function getIdRoles() {
        return $this->idRoles;
    }

	/**
	 * Set pathSlug
	 *
	 * @param string $pathSlug
	 *
	 * @return Users
	 */
	public function setPathSlug($pathSlug)
	{
		$this->pathSlug = $pathSlug;

		return $this;
	}

	/**
	 * Get pathSlug
	 *
	 * @return string
	 */
	public function getPathSlug()
	{
		return $this->pathSlug;
	}










	public function getUsername() {
		return $this->login;
	}

	public function getSalt() {
		return null;
	}

	public function getPassword() {
		return $this->passwordHash;
	}

	public function getRoles() {
		return [$this->getIdRoles()->getCode()];
	}

	public function eraseCredentials() {
	}

	public function serialize() {
		return serialize(array(
			$this->id,
			$this->login,
			$this->passwordHash,
		));
	}

	public function unserialize($serialized) {
		list (
			$this->id,
			$this->login,
			$this->passwordHash,
			) = unserialize($serialized);
	}
}
