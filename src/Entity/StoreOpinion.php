<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="StoreOpinionRepository")
 */
class StoreOpinion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Store", inversedBy="storeOpinions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $store;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="storeOpinions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StoreOpinionVote", mappedBy="storeOpinion", orphanRemoval=true)
     */
    private $storeOpinionVotes;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $identifier;

    public function __construct()
    {
        $this->storeOpinionVotes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

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
            $storeOpinionVote->setStoreOpinion($this);
        }

        return $this;
    }

    public function removeStoreOpinionVote(StoreOpinionVote $storeOpinionVote): self
    {
        if ($this->storeOpinionVotes->contains($storeOpinionVote)) {
            $this->storeOpinionVotes->removeElement($storeOpinionVote);
            // set the owning side to null (unless already changed)
            if ($storeOpinionVote->getStoreOpinion() === $this) {
                $storeOpinionVote->setStoreOpinion(null);
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
}
