<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table( name: '`category`')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private ?int $id = null;

    
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    #[Assert\NotBlank(message: 'Le nom de la catégorie est obligatoire !')]
    #[Assert\Length(min: 0, max: 255, minMessage: 'Le nom de la catégorie doit avoir 3 caractères minimum', maxMessage: 'le nom de la catégorie dépasse 255 caractères')]
    private ?string $name = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $slug = null;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, \App\Entity\Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    private \Doctrine\Common\Collections\Collection $products;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'categories')]
    private ?\App\Entity\User $owner = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        // set the owning side to null (unless already changed)
        if ($this->products->removeElement($product) && $product->getCategory() === $this) {
            $product->setCategory(null);
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
