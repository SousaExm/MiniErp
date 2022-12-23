<?php

namespace MiniErp\Infra\Repository\Order;

use MiniErp\Domain\Order\Order;
use MiniErp\Domain\Order\OrderWithProducts;
use MiniErp\Domain\Product\ProductWithQuantity;
use MiniErp\Domain\Repository\Order\OrderProductsRepository;
use MiniErp\Domain\Repository\Product\ProductRepository;
use PDO;

class OrderProductsRepositoryPDO implements OrderProductsRepository
{
  private PDO $pdo;
  private ProductRepository $productRepository;
  
  public function __construct(PDO $pdo, ProductRepository $productRepository)
  {
    $this->$productRepository;
    $this->pdo = $pdo;
  }

	public function getProducts(Order $order): OrderWithProducts 
  {
    $query = 'SELECT * FROM productPerOrder
              WHERE orderId = :orderId';
    $stmt = $this->pdo->prepare($query);
    
    $stmt->execute([
      ':orderId' => $order->uuid()
    ]);
    $productsPerOrderData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $this->hydrateOrderWithProducts($order, $productsPerOrderData);
	}
	
	public function save(OrderWithProducts $order): OrderWithProducts 
  {
    $this->insert($order);
    $this->update($order);
    $this->delete($order);

    return $this->getProducts($order->order);
	}

  private function insert(OrderWithProducts $orderWithProducts)
  {
    $productsForInsert = $orderWithProducts->lastAddedProducts();
    $orderId = $orderWithProducts->order()->uuid();
    
    if(count($productsForInsert) == 0){
      return;
    }

    $query = 'INSERT INTO productsPerOrder
              (productId, orderId, quantity)
              values (:productId, :orderId, :quantity)';
    $stmt = $this->pdo->prepare($query);

    foreach($productsForInsert as $product)
    {
      $productId = $product->productInfo->uuid();
      $quantity = $product->quantity();
      $stmt->execute([
        ':productId' => $productId,
        ':orderId' => $orderId,
        ':quantity' => $quantity
      ]);
    }
  }
  private function update(OrderWithProducts $orderWithProducts)
  {
    $productsForUpdate = $orderWithProducts->lastUpdatedProducts();
    $orderId = $orderWithProducts->order()->uuid();
    
    if(count($productsForUpdate) == 0){
      return;
    }

    $query = 'UPDATE productsPerOrder 
              SET quantity = :quantity
              WHERE orderId = :orderId and productId = :productId';


    $stmt = $this->pdo->prepare($query);

    foreach($productsForUpdate as $product)
    {
      $productId = $product->productInfo->uuid();
      $quantity = $product->quantity();
      $stmt->execute([
        ':productId' => $productId,
        ':orderId' => $orderId,
        ':quantity' => $quantity
      ]);
    }
  }

  private function delete(OrderWithProducts $orderWithProducts)
  {
    $productsForRemove = $orderWithProducts->lastRemovedProducts();
    $orderId = $orderWithProducts->order()->uuid();
    
    if(count($productsForRemove) == 0){
      return;
    }

    $query = 'DELETE FROM productsPerOrder
              WHERE orderId = :orderId and productId = :productId';


    $stmt = $this->pdo->prepare($query);

    foreach($productsForRemove as $product)
    {
      $productId = $product->productInfo->uuid();
      $stmt->execute([
        ':productId' => $productId,
        ':orderId' => $orderId
      ]);
    }
  }
	
	public function removeAll(Order $order): void
  {
    $query = 'DELETE FROM productPerOrder
              WHERE orderId = :orderId';
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([
      ':orderId' => $order->uuid()
    ]);
	}


  private function hydrateOrderWithProducts(Order $order, array $productsPerOrderData): OrderWithProducts
  {
    $productsList = $this->hydrateProductsWithQuantityLits($productsPerOrderData);
    return new OrderWithProducts($order, ...$productsList);
  }

  /**
   * @return ProductWithQuantity[]
   */
  private function hydrateProductsWithQuantityLits(array $productsPerOrderData): array
  {
    $products = [];
    foreach($productsPerOrderData as $productData)
    {
      [$productId, $quantity] = $productData;
      $product = $this->productRepository->getProductById($productId);
      $products[] = new ProductWithQuantity($product, $quantity);
    }
    return $products;  
  }
}