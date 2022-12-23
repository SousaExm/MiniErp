<?php

namespace MiniErp\Domain\Product;

class ProductWithQuantity
{
  public Product $productInfo;
  private float $quantity;
  private float $amount;
  public function __construct(Product $product, float $quantity)
  { 
    $this->productInfo = $product;
    $this->updateQuantity($quantity);
  }

  public function updateQuantity(float $quantityUpdated)
  {
    if($quantityUpdated <= 0){
      throw new \InvalidArgumentException('A quantiadade de um produto deve ser positiva');
    }
    $this->quantity = $quantityUpdated; 
  }

  public function amount(): float
  {
    $this->amount = $this->quantity * $this->productInfo->amount();
    return $this->amount;
  }

  public function quantity(): float
  {
    return $this->quantity;
  }
}