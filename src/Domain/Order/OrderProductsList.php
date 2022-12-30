<?php

namespace MiniErp\Domain\Order;

use DomainException;
use MiniErp\Domain\Order\OrderProduct;

class OrderProductsList 
{
  private array $list;
  public function __construct(string $productId, float $quantity)
  {
    $this->list = [];
    $this->addProduct($productId, $quantity);
  }

  public function addProduct(string $productId, float $quantity)
  {
    $product = new OrderProduct($productId, $quantity);
    if(!$this->productExistsInList($product->id())){
      $this->list[] = $product;
      return;
    }
   
    $newQuantity = $product->quantity();
    $indexProduct = $this->productPositionInList($product->id());
    $theSameProductInList =  $this->list[$indexProduct];

    $theSameProductInList->updateQuantity($newQuantity);
  }

  public function removeProduct(string $productId)
  {
    if(!$this->productExistsInList($productId)){
      throw new DomainException('O produto nao foi encontrado na lista de produtos');
    }

    if(count($this->list) == 1){
      throw new DomainException('Nao é permitido excluir todos os items de um pedido, para tal, exclua o pedido');
    }

    $indexProduct = $this->productPositionInList($productId);
    unset($this->list[$indexProduct]);
    $this->list = array_values($this->list);   
  }

  private function productExistsInList(string $productId)
  {
    foreach($this->list as $productInList)
    {
      if($productInList->id() === $productId){
        return true;
      }
    }
    return false;
  }

  private function productPositionInList(string $productId): int
  {
    foreach($this->list as $key => $productInList)
    {
      if($productInList->id() === $productId){
        return $key;
      }
    }
    return -1;
  }

  public function totalAmount(): float
  {
    $total = 0;
    foreach($this->list as $product)
    {
      $total += $product->totalPrice();
    }
    return $total;
  }

  public function list()
  {
    return $this->list;
  }
}