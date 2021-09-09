<?php

namespace App\Cart;

use App\Entity\Product;

class CartItem
{

    public $product;
    public $qty;

    public function __construct(Product $product, int $qty)
    {
        $this->qty = $qty;
        $this->product = $product;
    }

    public function getTotal(): int
    {
        return $this->product->getPrice() * $this->qty;
    }
}
