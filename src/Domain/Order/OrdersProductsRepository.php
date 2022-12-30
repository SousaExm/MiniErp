<?php

namespace MiniErp\Domain\Order;

use MiniErp\Domain\Order\OrderProduct;
use MiniErp\Domain\Order\OrderProductsList;
use MiniErp\Domain\Repository\Product\ProductRepository;

abstract class OrdersProductRepository 
{
  private ProductRepository $productRepository;

  public function __construct(ProductRepository $productRepository)
  {
    $this->productRepository = $productRepository;
  }

  private function checkProductIsRealAndGetPrice(OrderProduct $orderProduct)
  {
    $productId = $orderProduct->productId();
    $product = $this->productRepository->getProductById($productId);

    if($product === null){
      throw new \DomainException('Um dos produtos da lista do pedido nao foi encontrado');
    }

    $orderProduct->priceAtTimeOfOrder($product->amount());
    return $orderProduct;
  }

  public function save(string $orderId, OrderProductsList $orderProductsList)
  {
    if($this->getAllProductsFromTheOrder($orderId) === null){
      $this->insertAllProductsOrder($orderId, $orderProductsList);
      return;
    }

    $this->updateProductsOrder($orderId, $orderProductsList);
  }

  private function updateProductsOrder(string $orderId, OrderProductsList $orderProductsList)
  {
    foreach($orderProductsList as $orderProduct)
    {
      $productWithPrice = $this->checkProductIsRealAndGetPrice($orderProduct);
      
      if($this->getOneProductFromTheOrder($orderId, $productWithPrice->productId()) !== null){  
        $this->updateProductFromTheOrder($orderId, $productWithPrice);
        continue;
      }
      $this->insertOneProductInTheOrder($orderId, $productWithPrice);
    }
  }
  
  private function insertAllProductsOrder(string $orderId, OrderProductsList $orderProductsList)
  {
    foreach($orderProductsList as $orderProduct)
    {
      $productWithPrice = $this->checkProductIsRealAndGetPrice($orderProduct);
      $this->insertOneProductInTheOrder($orderId, $productWithPrice);
    }
  }
  
  public abstract function getAllProductsFromTheOrder(string $orderId): OrderProductsList|null;
  protected abstract function getOneProductFromTheOrder(string $orderId, string $productId): OrderProduct|null;
  protected abstract function insertOneProductInTheOrder(string $orderId, OrderProduct $product): void;  
  protected abstract function updateProductFromTheOrder(string $orderId, OrderProduct $orderProductsList): void;
}
