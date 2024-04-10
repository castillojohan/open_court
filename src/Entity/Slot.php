<?php

namespace App\Entity;

use App\Repository\SlotRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SlotRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Slot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("get_slots")]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Groups("get_slots")]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(DateTimeImmutable::class)]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\ManyToOne(inversedBy: 'slots')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Court $court = null;

    #[ORM\ManyToOne(inversedBy: 'slots')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups("get_slots")]
    #[Assert\NotBlank]
    #[Assert\Type(Member::class)]
    private ?Member $memb = null;

    #[ORM\ManyToOne(inversedBy: 'slots')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups("get_slots")]
    private ?Lesson $lesson = null ;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    /**
     * setEndAt will automaticaly set endDate to startDate +1 hour
     *
     * @return static
     */
    public function setEndAt(): static
    {
        $this->endAt = $this->getStartAt()->modify('+1 hour');

        return $this;
    }

    public function getCourt(): ?Court
    {
        return $this->court;
    }

    public function setCourt(?Court $court): static
    {
        $this->court = $court;

        return $this;
    }

    public function getMemb(): ?Member
    {
        return $this->memb;
    }

    public function setMemb(?Member $memb): static
    {
        $this->memb = $memb;

        return $this;
    }

    public function getLesson()
    {
        return $this->lesson;
    }
}
