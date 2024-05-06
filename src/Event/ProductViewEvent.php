<?php 
namespace App\Event;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductViewEvent extends Event{

public function __construct(private readonly \App\Entity\Product $product)
{
}
public function GetProduct() : Product
{
    return $this->product ;
}

}