<?php

namespace MiniErp\Domain\Order;

use DomainException;

class OrderProduct
{
  private string $productId;
  private float $quantity;
  private float | null $price;

  public function __construct(string $productId, float $quantity)
  {
    $this->productId = $productId;
    $this->updateQuantity($quantity);
    $this->price = null;
  }

  public function updateQuantity(float $quantity)
  {
    if($quantity <= 0){
      throw new DomainException('Nao é permitido ter um produto com sua quantidade negativa ou zerada.');
    }
    $this->quantity = $quantity;
  }

  public function priceAtTimeOfOrder(float $price)
  {
    if($price <= 0){
      throw new DomainException('Um produto nao pode ter valor zerado ou negativo.');
    }
    $this->price = $price;
  }

  public function productId()
  {
    return $this->productId;
  }

  public function quantity(): float
  {
    return $this->quantity;
  }

  public function id(): string
  {
    return $this->productId;
  }

  public function totalPrice(): float
  {
    if($this->price == null){
      throw new DomainException('Nao é possível calcular o total de um produto ou pedido sem definir sem definir o preço dos itens.');
    }
    return $this->quantity * $this->price;
  }

  public function unitPrice()
  {
    return $this->price;
  }
}
