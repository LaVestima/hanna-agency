<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

//    /**
//     * @ORM\Column(type="boolean")
//     */
//    private $isRead = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conversation", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conversation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $readAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

//    public function getIsRead(): ?bool
//    {
//        return $this->isRead;
//    }
//
//    public function setIsRead(bool $isRead): self
//    {
//        $this->isRead = $isRead;
//
//        return $this;
//    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

//    public function getIsFromInitiator(): ?bool
//    {
//        return $this->isFromInitiator;
//    }
//
//    public function setIsFromInitiator(bool $isFromInitiator): self
//    {
//        $this->isFromInitiator = $isFromInitiator;
//
//        return $this;
//    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getReadAt(): ?\DateTimeInterface
    {
        return $this->readAt;
    }

    public function setReadAt(?\DateTimeInterface $readAt): self
    {
        $this->readAt = $readAt;

        return $this;
    }
}
