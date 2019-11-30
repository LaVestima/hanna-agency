<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConversationRepository")
 */
class Conversation implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $identifier;

//    /**
//     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="conversationsSent")
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $userFrom;
//
//    /**
//     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="conversationsReceived")
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $userTo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="conversation", orphanRemoval=true)
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

//    public function getUserFrom(): ?User
//    {
//        return $this->userFrom;
//    }
//
//    public function setUserFrom(?User $userFrom): self
//    {
//        $this->userFrom = $userFrom;
//
//        return $this;
//    }
//
//    public function getUserTo(): ?User
//    {
//        return $this->userTo;
//    }
//
//    public function setUserTo(?User $userTo): self
//    {
//        $this->userTo = $userTo;
//
//        return $this;
//    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }
}
