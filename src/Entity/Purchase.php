<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
#[ORM\Table(name: '`order`')]
#[ORM\HasLifecycleCallbacks]
class Purchase
{
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_PAID = 'PAID';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $fullname = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $adress = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $postalCode = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private ?int $total = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $status = 'PENDING';

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'purchases')]
    private ?\App\Entity\User $user = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $purchasedAt = null;

    /**
     * @var Collection<PurchaseItem>
     */
    #[ORM\OneToMany(mappedBy: 'purchase', targetEntity: PurchaseItem::class, orphanRemoval: true)]
    private \Doctrine\Common\Collections\Collection $purchaseItems;



    public function __construct()
    {
        $this->purchaseItems = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        if (empty($this->purchasedAt)) {
            $this->purchasedAt = new DateTime();
        }
    }
    #[ORM\PreFlush]
    public function preFlush()
    {
        $total = 0;

        foreach ($this->purchaseItems as $item) {
            $total += $item->getTotal();
        }
          $this->total = $total;
         
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPurchasedAt(): ?\DateTimeInterface
    {
        return $this->purchasedAt;
    }

    public function setPurchasedAt(\DateTimeInterface $purchasedAt): self
    {
        $this->purchasedAt = $purchasedAt;

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
            $purchaseItem->setPurchase($this);
        }

        return $this;
    }

    public function removePurchaseItem(PurchaseItem $purchaseItem): self
    {
        // set the owning side to null (unless already changed)
        if ($this->purchaseItems->removeElement($purchaseItem) && $purchaseItem->getPurchase() === $this) {
            $purchaseItem->setPurchase(null);
        }

        return $this;
    }
}
