<?php

namespace App\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersister
{


    public function __construct(protected \Symfony\Bundle\SecurityBundle\Security  $security, protected CartService $cartService, protected \Doctrine\ORM\EntityManagerInterface $em)
    {
    }

    public function storePurchase(Purchase $purchase): void
    {
        $purchase->setUser($this->security->getUser());

        $this->em->persist($purchase);

        foreach ($this->cartService->getDetailCartItem() as $cartItem) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setProductPrice($cartItem->product->getPrice())
                ->setQuantity($cartItem->qty)
                ->setTotal($cartItem->getTotal());
                $purchase->addPurchaseItem($purchaseItem);
            $this->em->persist($purchaseItem);

        }

        $this->em->flush();
    }
}
