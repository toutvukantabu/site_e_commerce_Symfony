<?php

namespace App\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersister
{
    protected $security;
    protected $cartservice;
    protected $em;


    public function __construct(Security  $security, CartService $cartService, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;
    }

    public function storePurchase(Purchase $purchase)
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
