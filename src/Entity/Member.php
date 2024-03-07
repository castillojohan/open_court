<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\Table(name: '`member`')]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(
        message : "Ne dois pas être vide"
    )]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[Assert\NotBlank(
        message : "Ne dois pas être vide"
    )]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[Assert\NotNull]
    #[Assert\NotBlank(
        message : "Ne dois pas être vide"
    )]
    #[Assert\Type("\DateTimeImmutable")]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $birthday = null;

    private ?int $age = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'memb', targetEntity: Slot::class)]
    private Collection $slots;

    #[ORM\OneToMany(mappedBy: 'memb', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\Column]
    private ?bool $gender = null;

    #[ORM\Column(nullable: true)]
    private ?string $pinCode = null;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDay(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeImmutable $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getAge()
    {
        $this->setAge();
        return $this->age;
    }

    /**
     * function setAge() take dateTimeNow and compare to member birthday date
     * converted to years diff only and forced to be an integer
     * @return void
     */
    public function setAge()
    {
        $dateNowObject = new DateTimeImmutable();
        $birthdayObject = $this->getBirthDay();
        $diff = $dateNowObject->diff($birthdayObject)->format('%Y');
        $this->age = $diff;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Slot>
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(Slot $slot): static
    {
        if (!$this->slots->contains($slot)) {
            $this->slots->add($slot);
            $slot->setMemb($this);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): static
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getMemb() === $this) {
                $slot->setMemb(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setMemb($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getMemb() === $this) {
                $article->setMemb(null);
            }
        }

        return $this;
    }

    public function isGender(): ?bool
    {
        return $this->gender;
    }

    public function setGender(bool $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPinCode(): ?string
    {
        return $this->pinCode;
    }

    public function setPinCode(?string $pinCode): static
    {
        $this->pinCode = $pinCode;

        return $this;
    }
}
