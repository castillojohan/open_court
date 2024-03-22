<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: LessonRepository::class)]
class Lesson 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    private ?string $name = null;
    
    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\OneToMany(mappedBy:'lesson', targetEntity: Slot::class)]
    private Collection $slots;

    #[ORM\ManyToMany(targetEntity: Member::class, inversedBy: 'lessons')]
    private Collection $lessonMember;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
        $this->lessonMember = new ArrayCollection();
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

    public function getSlots(): Collection
    {
        return $this->slots;
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


}