<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Recipie::class, mappedBy: 'category')]
    private Collection $recipie;

    public function __construct()
    {
        $this->recipie = new ArrayCollection();
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
    public function getRecipie(): Collection
    {
        return $this->recipie;
    }

    public function addRecipie(Recipie $recipie): self
    {
        if (!$this->recipie->contains($recipie)) {
            $this->recipie->add($recipie);
        }

        return $this;
    }

    public function removeRecipie(Recipie $recipie): self
    {
        $this->recipie->removeElement($recipie);

        return $this;
    }
}
