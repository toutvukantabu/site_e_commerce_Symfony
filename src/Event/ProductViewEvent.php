<?php 
namespace App\Event;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductViewEvent extends Event{

private $product;

public function __construct(Product $product)
{
    $this->product = $product;
}
public function GetProduct() : Product
{
    return $this->product ;
}

}