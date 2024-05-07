<?php

namespace App\Cart;

use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(private readonly RequestStack $requestStack, protected \App\Repository\ProductRepository $productRepository)
    {
    }

    protected function getCart(): array
    {
        return $this->requestStack->getSession()->get('cart', []);
    }

    protected function saveCart(array $cart): void
    {
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function empty()
    {
        $this->saveCart([]);
    }

    public function add(int $id): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (array_key_exists($id, $cart)) {
            ++$cart[$id];
        } else {
            $cart[$id] = 1;
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function decrement(int $id)
    {
        $cart = $this->getCart();
        if (!array_key_exists($id, $cart)) {
            return;
        }
        if (1 === $cart[$id]) {
            $this->remove($id);

            return;
        }
        --$cart[$id];
        $this->saveCart($cart);
    }

    public function remove(int $id)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        unset($cart[$id]);
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->requestStack->getSession()->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }
            $total += $product->getPrice() * $qty;
        }

        return $total;
    }

    /**
     * @return CartItem[]
     */
    public function getDetailCartItem(): array
    {
        $detailedcart = [];
        foreach ($this->requestStack->getSession()->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }
            $detailedcart[] = new CartItem($product, $qty);
        }

        return $detailedcart;
    }
}
