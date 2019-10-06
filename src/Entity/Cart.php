<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 */
class Cart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $sessionId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="carts")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CartProductVariant", mappedBy="cart", orphanRemoval=true)
     */
    private $cartProductVariants;

    public function __construct()
    {
        $this->cartProductVariants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
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

    /**
     * @return Collection|CartProductVariant[]
     */
    public function getCartProductVariants(): Collection
    {
        return $this->cartProductVariants;
    }

    public function addCartProductVariant(CartProductVariant $cartProductVariant): self
    {
        if (!$this->cartProductVariants->contains($cartProductVariant)) {
            $this->cartProductVariants[] = $cartProductVariant;
            $cartProductVariant->setCart($this);
        }

        return $this;
    }

    public function removeCartProductVariant(CartProductVariant $cartProductVariant): self
    {
        if ($this->cartProductVariants->contains($cartProductVariant)) {
            $this->cartProductVariants->removeElement($cartProductVariant);
            // set the owning side to null (unless already changed)
            if ($cartProductVariant->getCart() === $this) {
                $cartProductVariant->setCart(null);
            }
        }

        return $this;
    }
}
