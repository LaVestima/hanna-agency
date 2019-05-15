<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="User_Login_U", columns={"login"}),
 *     @ORM\UniqueConstraint(name="User_Email_U", columns={"email"}),
 *     @ORM\UniqueConstraint(name="User_Password_Hash_U", columns={"password_hash"})
 * }, indexes={
 *     @ORM\Index(name="User_Role_FK", columns={"role_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable, EntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id()
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDeleted;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $passwordHash;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $pathSlug = '';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="user", orphanRemoval=true)
     */
    private $addresses;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Token", mappedBy="user", orphanRemoval=true)
     */
    private $tokens;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSetting", mappedBy="user", orphanRemoval=true)
     */
    private $userSettings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LoginAttempt", mappedBy="user")
     */
    private $loginAttempts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Producer", mappedBy="user", orphanRemoval=true)
     */
    private $producers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="user")
     */
    private $orders;



    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->tokens = new ArrayCollection();
        $this->userSettings = new ArrayCollection();
        $this->loginAttempts = new ArrayCollection();
        $this->producers = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function __toString() {
        return (string)$this->id;
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
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
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
     * Set pathSlug
     *
     * @param string $pathSlug
     *
     * @return User
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
        return [$this->getRole()->getCode()];
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

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|Token[]
     */
    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    public function addToken(Token $token): self
    {
        if (!$this->tokens->contains($token)) {
            $this->tokens[] = $token;
            $token->setUser($this);
        }

        return $this;
    }

    public function removeToken(Token $token): self
    {
        if ($this->tokens->contains($token)) {
            $this->tokens->removeElement($token);
            // set the owning side to null (unless already changed)
            if ($token->getUser() === $this) {
                $token->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserSetting[]
     */
    public function getUserSettings(): Collection
    {
        return $this->userSettings;
    }

    public function addUserSetting(UserSetting $userSetting): self
    {
        if (!$this->userSettings->contains($userSetting)) {
            $this->userSettings[] = $userSetting;
            $userSetting->setUser($this);
        }

        return $this;
    }

    public function removeUserSetting(UserSetting $userSetting): self
    {
        if ($this->userSettings->contains($userSetting)) {
            $this->userSettings->removeElement($userSetting);
            // set the owning side to null (unless already changed)
            if ($userSetting->getUser() === $this) {
                $userSetting->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LoginAttempt[]
     */
    public function getLoginAttempts(): Collection
    {
        return $this->loginAttempts;
    }

    public function addLoginAttempt(LoginAttempt $loginAttempt): self
    {
        if (!$this->loginAttempts->contains($loginAttempt)) {
            $this->loginAttempts[] = $loginAttempt;
            $loginAttempt->setUser($this);
        }

        return $this;
    }

    public function removeLoginAttempt(LoginAttempt $loginAttempt): self
    {
        if ($this->loginAttempts->contains($loginAttempt)) {
            $this->loginAttempts->removeElement($loginAttempt);
            // set the owning side to null (unless already changed)
            if ($loginAttempt->getUser() === $this) {
                $loginAttempt->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Producer[]
     */
    public function getProducers(): Collection
    {
        return $this->producers;
    }

    public function addProducer(Producer $producer): self
    {
        if (!$this->producers->contains($producer)) {
            $this->producers[] = $producer;
            $producer->setUser($this);
        }

        return $this;
    }

    public function removeProducer(Producer $producer): self
    {
        if ($this->producers->contains($producer)) {
            $this->producers->removeElement($producer);
            // set the owning side to null (unless already changed)
            if ($producer->getUser() === $this) {
                $producer->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }
}
