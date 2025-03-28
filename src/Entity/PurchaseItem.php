<?php

namespace App\Entity;

use App\Repository\PurchaseItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseItemRepository::class)]
#[ORM\Table(name: '`purchase_item`')]
class PurchaseItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'purchaseItems')]
    private ?\App\Entity\Product $product = null;

    #[ORM\ManyToOne(targetEntity: Purchase::class, inversedBy: 'purchaseItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?\App\Entity\Purchase $purchase = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $productName = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private ?int $productPrice = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private ?int $quantity = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private ?int $total = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    public function setPurchase(?Purchase $purchase): self
    {
        $this->purchase = $purchase;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?int
    {
        return $this->productPrice;
    }

    public function setProductPrice(int $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }
}
