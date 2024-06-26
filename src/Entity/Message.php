<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('get_conversation')]
    #[Assert\NotBlank(
        message : "Ne dois pas être vide."
    )]
    #[Assert\NotNull(
        message : "Ne dois pas être null."
    )]
    #[Assert\Length(
        max: 255
    )]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups('get_conversation')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $isRead = null;

    #[ORM\ManyToOne(inversedBy: 'sent')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('get_conversation')]
    #[Assert\Type(Member::class)]
    private ?Member $sender = null;

    #[ORM\ManyToOne(inversedBy: 'received')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('get_conversation')]
    #[Assert\Type(Member::class)]
    private ?Member $recipient = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->isRead = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSender(): ?Member
    {
        return $this->sender;
    }

    public function setSender(?Member $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?Member
    {
        return $this->recipient;
    }

    public function setRecipient(?Member $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function readMessage()
    {
        $this->isRead = true;
        return $this;
    }

    public function getIsReadStatus()
    {
        return $this->isRead;
    }
}
