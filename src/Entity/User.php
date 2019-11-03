<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="User_Login_U", columns={"login"}),
 *     @ORM\UniqueConstraint(name="User_Email_U", columns={"email"}),
 *     @ORM\UniqueConstraint(name="User_Password_Hash_U", columns={"password_hash"})
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
     * @Assert\Email(
     *     mode="html5"
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $passwordHash;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $addresses;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="user")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PageVisit", mappedBy="user")
     */
    private $pageVisits;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Store", mappedBy="admin")
     */
    private $stores;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Conversation", mappedBy="userFrom")
     */
    private $conversationsSent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Conversation", mappedBy="userTo")
     */
    private $conversationsReceived;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductReview", mappedBy="user")
     */
    private $productReviews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StoreSubuser", mappedBy="user", orphanRemoval=true)
     */
    private $storeSubusers;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $identifier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StoreOpinion", mappedBy="user", orphanRemoval=true)
     */
    private $storeOpinions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StoreOpinionVote", mappedBy="user")
     */
    private $storeOpinionVotes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cart", mappedBy="user")
     */
    private $carts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MLModel", mappedBy="user", orphanRemoval=true)
     */
    private $MLModels;

    /**
     * @ORM\Column(type="integer")
     */
    private $gender;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->tokens = new ArrayCollection();
        $this->userSettings = new ArrayCollection();
        $this->loginAttempts = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->pageVisits = new ArrayCollection();
        $this->stores = new ArrayCollection();
        $this->conversationsSent = new ArrayCollection();
        $this->conversationsReceived = new ArrayCollection();
        $this->productReviews = new ArrayCollection();
        $this->storeSubusers = new ArrayCollection();
        $this->storeOpinions = new ArrayCollection();
        $this->storeOpinionVotes = new ArrayCollection();
        $this->carts = new ArrayCollection();
        $this->MLModels = new ArrayCollection();
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

    public function getUsername() {
        return $this->login;
    }

    public function getSalt() {
        return null;
    }

    public function getPassword() {
        return $this->passwordHash;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles() {
//        return [$this->getRole()->getCode(), 'ROLE_BBB'];

        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function addRole(string $role)
    {
        $this->roles[] = $role;
    }

    public function addRoles(array $roles)
    {
        $this->roles = array_merge($this->roles, $roles);
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

    /**
     * @return Collection|PageVisit[]
     */
    public function getPageVisits(): Collection
    {
        return $this->pageVisits;
    }

    public function addPageVisit(PageVisit $pageVisit): self
    {
        if (!$this->pageVisits->contains($pageVisit)) {
            $this->pageVisits[] = $pageVisit;
            $pageVisit->setUser($this);
        }

        return $this;
    }

    public function removePageVisit(PageVisit $pageVisit): self
    {
        if ($this->pageVisits->contains($pageVisit)) {
            $this->pageVisits->removeElement($pageVisit);
            // set the owning side to null (unless already changed)
            if ($pageVisit->getUser() === $this) {
                $pageVisit->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Store[]
     */
    public function getStores(): Collection
    {
        return $this->stores;
    }

    public function addStore(Store $store): self
    {
        if (!$this->stores->contains($store)) {
            $this->stores[] = $store;
            $store->setAdmin($this);
        }

        return $this;
    }

    public function removeStore(Store $store): self
    {
        if ($this->stores->contains($store)) {
            $this->stores->removeElement($store);
            // set the owning side to null (unless already changed)
            if ($store->getAdmin() === $this) {
                $store->setAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversationsSent(): Collection
    {
        return $this->conversationsSent;
    }

    public function addConversationsSent(Conversation $conversationsSent): self
    {
        if (!$this->conversationsSent->contains($conversationsSent)) {
            $this->conversationsSent[] = $conversationsSent;
            $conversationsSent->setUserFrom($this);
        }

        return $this;
    }

    public function removeConversationsSent(Conversation $conversationsSent): self
    {
        if ($this->conversationsSent->contains($conversationsSent)) {
            $this->conversationsSent->removeElement($conversationsSent);
            // set the owning side to null (unless already changed)
            if ($conversationsSent->getUserFrom() === $this) {
                $conversationsSent->setUserFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversationsReceived(): Collection
    {
        return $this->conversationsReceived;
    }

    public function addConversationsReceived(Conversation $conversationsReceived): self
    {
        if (!$this->conversationsReceived->contains($conversationsReceived)) {
            $this->conversationsReceived[] = $conversationsReceived;
            $conversationsReceived->setUserTo($this);
        }

        return $this;
    }

    public function removeConversationsReceived(Conversation $conversationsReceived): self
    {
        if ($this->conversationsReceived->contains($conversationsReceived)) {
            $this->conversationsReceived->removeElement($conversationsReceived);
            // set the owning side to null (unless already changed)
            if ($conversationsReceived->getUserTo() === $this) {
                $conversationsReceived->setUserTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductReview[]
     */
    public function getProductReviews(): Collection
    {
        return $this->productReviews;
    }

    public function addProductReview(ProductReview $productReview): self
    {
        if (!$this->productReviews->contains($productReview)) {
            $this->productReviews[] = $productReview;
            $productReview->setUser($this);
        }

        return $this;
    }

    public function removeProductReview(ProductReview $productReview): self
    {
        if ($this->productReviews->contains($productReview)) {
            $this->productReviews->removeElement($productReview);
            // set the owning side to null (unless already changed)
            if ($productReview->getUser() === $this) {
                $productReview->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StoreSubuser[]
     */
    public function getStoreSubusers(): Collection
    {
        return $this->storeSubusers;
    }

    public function addStoreSubuser(StoreSubuser $storeSubuser): self
    {
        if (!$this->storeSubusers->contains($storeSubuser)) {
            $this->storeSubusers[] = $storeSubuser;
            $storeSubuser->setUser($this);
        }

        return $this;
    }

    public function removeStoreSubuser(StoreSubuser $storeSubuser): self
    {
        if ($this->storeSubusers->contains($storeSubuser)) {
            $this->storeSubusers->removeElement($storeSubuser);
            // set the owning side to null (unless already changed)
            if ($storeSubuser->getUser() === $this) {
                $storeSubuser->setUser(null);
            }
        }

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|StoreOpinion[]
     */
    public function getStoreOpinions(): Collection
    {
        return $this->storeOpinions;
    }

    public function addStoreOpinion(StoreOpinion $storeOpinion): self
    {
        if (!$this->storeOpinions->contains($storeOpinion)) {
            $this->storeOpinions[] = $storeOpinion;
            $storeOpinion->setUser($this);
        }

        return $this;
    }

    public function removeStoreOpinion(StoreOpinion $storeOpinion): self
    {
        if ($this->storeOpinions->contains($storeOpinion)) {
            $this->storeOpinions->removeElement($storeOpinion);
            // set the owning side to null (unless already changed)
            if ($storeOpinion->getUser() === $this) {
                $storeOpinion->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StoreOpinionVote[]
     */
    public function getStoreOpinionVotes(): Collection
    {
        return $this->storeOpinionVotes;
    }

    public function addStoreOpinionVote(StoreOpinionVote $storeOpinionVote): self
    {
        if (!$this->storeOpinionVotes->contains($storeOpinionVote)) {
            $this->storeOpinionVotes[] = $storeOpinionVote;
            $storeOpinionVote->setUser($this);
        }

        return $this;
    }

    public function removeStoreOpinionVote(StoreOpinionVote $storeOpinionVote): self
    {
        if ($this->storeOpinionVotes->contains($storeOpinionVote)) {
            $this->storeOpinionVotes->removeElement($storeOpinionVote);
            // set the owning side to null (unless already changed)
            if ($storeOpinionVote->getUser() === $this) {
                $storeOpinionVote->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->setUser($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->contains($cart)) {
            $this->carts->removeElement($cart);
            // set the owning side to null (unless already changed)
            if ($cart->getUser() === $this) {
                $cart->setUser(null);
            }
        }

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|MLModel[]
     */
    public function getMLModels(): Collection
    {
        return $this->MLModels;
    }

    public function addMLModel(MLModel $mLModel): self
    {
        if (!$this->MLModels->contains($mLModel)) {
            $this->MLModels[] = $mLModel;
            $mLModel->setUser($this);
        }

        return $this;
    }

    public function removeMLModel(MLModel $mLModel): self
    {
        if ($this->MLModels->contains($mLModel)) {
            $this->MLModels->removeElement($mLModel);
            // set the owning side to null (unless already changed)
            if ($mLModel->getUser() === $this) {
                $mLModel->setUser(null);
            }
        }
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }
}
