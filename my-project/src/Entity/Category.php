<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category implements RecipieFieldCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Recipie::class, mappedBy: 'categories')]
    private Collection $recipies;

    public function __construct()
    {
        $this->recipies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Recipie>
     */
    public function getRecipies(): Collection
    {
        return $this->recipies;
    }

    public function addRecipie(Recipie $recipie): self
    {
        if (!$this->recipies->contains($recipie)) {
            $this->recipies->add($recipie);
            $recipie->addCategory($this);
        }

        return $this;
    }

    public function removeRecipie(Recipie $recipie): self
    {
        $this->recipies->removeElement($recipie);

        return $this;
    }
}
