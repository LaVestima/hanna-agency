<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoreOpinionVoteRepository")
 */
class StoreOpinionVote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StoreOpinion", inversedBy="storeOpinionVotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $storeOpinion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPositive;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="storeOpinionVotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStoreOpinion(): ?StoreOpinion
    {
        return $this->storeOpinion;
    }

    public function setStoreOpinion(?StoreOpinion $storeOpinion): self
    {
        $this->storeOpinion = $storeOpinion;

        return $this;
    }

    public function getIsPositive(): ?bool
    {
        return $this->isPositive;
    }

    public function setIsPositive(bool $isPositive): self
    {
        $this->isPositive = $isPositive;

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
}
