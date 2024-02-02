<?php

namespace App\Entity;

use App\Repository\CourtRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourtRepository::class)]
class Court
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 25)]
    private ?string $surface = null;

    #[ORM\Column]
    private ?bool $isFunctionnal = null;

    #[ORM\Column]
    private ?bool $isLighted = null;

    #[ORM\OneToMany(mappedBy: 'court', targetEntity: Slot::class, orphanRemoval: true)]
    private Collection $slots;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurface(): ?string
    {
        return $this->surface;
    }

    public function setSurface(string $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function isIsFunctionnal(): ?bool
    {
        return $this->isFunctionnal;
    }

    public function setIsFunctionnal(bool $isFunctionnal): static
    {
        $this->isFunctionnal = $isFunctionnal;

        return $this;
    }

    public function isIsLighted(): ?bool
    {
        return $this->isLighted;
    }

    public function setIsLighted(bool $isLighted): static
    {
        $this->isLighted = $isLighted;

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
            $slot->setCourt($this);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): static
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getCourt() === $this) {
                $slot->setCourt(null);
            }
        }

        return $this;
    }
}
