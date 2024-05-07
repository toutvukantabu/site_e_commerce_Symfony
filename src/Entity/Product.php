<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: '`product`')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    #[Assert\NotBlank(message: 'Le nom du produit est obligatoire !')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le nom du produit doit avoir 3 caractères minimum', maxMessage: 'le nom du produit dépasse 255 caractères')]
    private ?string $name = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    #[Assert\NotBlank(message: 'le produit doit contenir un prix')]
    private ?int $price = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    private ?Category $category = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)] // Assert/url("mettez une url valide pour l'image")
    #[Assert\NotBlank(message: 'Le nom du produit est obligatoire !')]
    private ?string $mainPicture = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT)]
    #[Assert\NotBlank(message: 'Le produit doit contenir une description')]
    #[Assert\Length(min: 20, minMessage: 'la description doit faire 20 caractères minimum')]
    private ?string $shortDescription = null;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, \App\Entity\PurchaseItem>
     */
    #[ORM\OneToMany(targetEntity: PurchaseItem::class, mappedBy: 'product')]
    private Collection $purchaseItems;

    public function __construct()
    {
        $this->purchaseItems = new ArrayCollection();
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(?string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return Collection|PurchaseItem[]
     */
    public function getPurchaseItems(): Collection
    {
        return $this->purchaseItems;
    }

    public function addPurchaseItem(PurchaseItem $purchaseItem): self
    {
        if (!$this->purchaseItems->contains($purchaseItem)) {
            $this->purchaseItems[] = $purchaseItem;
            $purchaseItem->setProduct($this);
        }

        return $this;
    }

    public function removePurchaseItem(PurchaseItem $purchaseItem): self
    {
        // set the owning side to null (unless already changed)
        if ($this->purchaseItems->removeElement($purchaseItem) && $purchaseItem->getProduct() === $this) {
            $purchaseItem->setProduct(null);
        }

        return $this;
    }
}
