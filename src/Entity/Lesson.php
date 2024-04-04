<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
class Lesson 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("get_slots")]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    #[Groups("get_slots")]
    private ?string $name = null;
    
    #[ORM\Column]
    private ?int $capacity = null;

    private ?int $actualCapacity = null;

    #[ORM\Column(nullable: true)]
    private ?bool $gender = null;

    #[ORM\OneToMany(mappedBy:'lesson', targetEntity: Slot::class)]
    private Collection $slots;

    #[ORM\ManyToOne(inversedBy: 'lessonsTeacher')]
    private Member $teacher;

    #[ORM\ManyToMany(targetEntity: Member::class, inversedBy: "lessons")]
    private Collection $lessonMember;

    #[ORM\ManyToOne(inversedBy: 'lessons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Court $court = null;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
        $this->lessonMember = new ArrayCollection();
    }

    public function __toString()
    {
        
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getCapacity()
    {
        return $this->capacity;
    }
    public function setCapacity($cap)
    {
        $this->capacity = $cap;
        return $this;
    }

    public function getGender(): ?bool
    {
        return $this->gender;
    }

    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function getTeacher(): ?Member
    {
        return $this->teacher;
    }

    public function setTeacher($memberTeacher)
    {
        $this->teacher = $memberTeacher;
        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getLessonMember(): Collection
    {
        return $this->lessonMember;
    }

    public function addLessonMember(Member $lessonMember): static
    {
        if (!$this->lessonMember->contains($lessonMember)) {
            $this->lessonMember->add($lessonMember);
        }

        return $this;
    }

    public function removeLessonMember(Member $lessonMember): static
    {
        $this->lessonMember->removeElement($lessonMember);

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

    public function getActualCapacity()
    {
        return $this->actualCapacity;
    }

    public function setActualCapacity($newCapacity)
    {
        $this->actualCapacity = $newCapacity;
    }

}