<?php

namespace MiniErp\Domain\Order;

use DomainException;
use MiniErp\Domain\Order\OrderProduct;

class OrderProductsList 
{
  private array $productsList;
  public function __construct(OrderProduct ...$products)
  {
    foreach($products as $product)
    {
      $this->addProduct($product);
    }
  }

  public function addProduct(OrderProduct $product)
  {
    if(!$this->productExistsInList($product->id())){
      $this->productsList[] = $product;
      return;
    }
   
    $newQuantity = $product->quantity();
    $indexProduct = $this->productPositionInList($product->id());
    $theSameProductInList =  $this->productsList[$indexProduct];

    $theSameProductInList->updateQuantity($newQuantity);
  }

  public function removeProduct(string $productId)
  {
    if($this->productExistsInList($productId)){
      throw new DomainException('O produto nao foi encontrado na lista de produtos');
    }

    if(count($this->productsList) == 1){
      throw new DomainException('Nao é permitido excluir todos os items de um pedido, para tal, exclua o pedido');
    }

    $indexProduct = $this->productPositionInList($productId);
    unset($this->productsList[$indexProduct]);
    $this->productsList = array_values($this->productsList);   
  }

  private function productExistsInList(string $productId)
  {
    foreach($this->productsList as $productInList)
    {
      if($productInList->id() === $productId){
        return true;
      }
    }
    return false;
  }

  private function productPositionInList(string $productId): int
  {
    foreach($this->productsList as $key => $productInList)
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
    foreach($this->productsList as $product)
    {
      $total += $product->totalPrice();
    }
    return $total;
  }
}