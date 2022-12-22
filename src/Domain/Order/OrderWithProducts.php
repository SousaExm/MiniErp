<?php

namespace MiniErp\Domain\Order;

use DomainException;
use MiniErp\Domain\Product\Product;
use MiniErp\Domain\Product\ProductWithQuantity;

class OrderWithProducts
{
  private Order $order;
  private float $totalAmount;
  private array $productWithQuantityList;
  private array $lastAddedProductsList;
  private array $lastRemovedProductsList;
  private array $lastUpdatedProductsList;

  public function __construct(Order $order, ProductWithQuantity ...$productWithQuantity)
  {
    $this->order = $order;
    $this->updateProductsList(...$productWithQuantity);
  }

  public function updateProductsList(ProductWithQuantity ...$updatedListOfProducts) 
  {
    $this->lastAddedProductsList = [];
    $this->lastUpdatedProductsList = [];
    $this->lastRemovedProductsList = [];

    foreach($updatedListOfProducts as $productOfUpdatedList)
    {
      if($this->isDuplicateProduct($productOfUpdatedList->productInfo, ...$updatedListOfProducts)){
        throw new DomainException('Existem produtos duplicados na lista, para adicionar produtos iguais altere a quantidade');
      }

      $indexOfOutdatedProduct = $this->isThisProductInList($productOfUpdatedList->productInfo, ...$this->productWithQuantityList);
      $isNewProduct = $indexOfOutdatedProduct == false ? true : false;

      if($isNewProduct){
        $this->addProduct($productOfUpdatedList);
        continue;
      }
      $this->updateProduct($productOfUpdatedList, $indexOfOutdatedProduct);
    }
    $this->removeProducts(...$updatedListOfProducts);
  }

  private function addProduct(ProductWithQuantity $productWithQuantity)
  {
    $this->productWithQuantityList[] = $productWithQuantity;
    $this->lastAddedProductsList[] = $productWithQuantity;
  }

  private function updateProduct(ProductWithQuantity $product, int $indexOfOutdatedProduct)
  {
    unset($this->productWithQuantityList, $indexOfOutdatedProduct);
    $this->productWithQuantityList[] = $product;
    $this->lastUpdatedProductsList[] = $product;
  }

  private function removeProducts(ProductWithQuantity ...$updatedList)
  {
    $indexOfProductsForRemove = [];
    foreach($this->productWithQuantityList as $key => $product)
    {
      $isProductNotFound = !$this->isThisProductInList($product->productInfo, ...$updatedList);
      
      if($isProductNotFound){
        $indexOfProductsForRemove[] = $key;
        $this->lastRemovedProductsList[] = $product;
      }
    }
    unset($this->productWithQuantityList, $indexOfProductsForRemove);
  }

  /**
   * @return false Se o produto nao existir na lista informada retorna false
   * @return int Se o produto existir na lista informada retorna o seu index
   */
  private function isThisProductInList(Product $product, ProductWithQuantity ...$listOfProducts): false | int
  {
    foreach($listOfProducts as $key => $productInList)
    {
      if($product == $productInList->productInfo){
        return $key;
      }
    }
    return false;
  }

  private function isDuplicateProduct(Product $product, ProductWithQuantity ...$listOfProducts): bool
  {
    $countOfTheSameProduct = 0;
    foreach($listOfProducts as $productInList)
    {
      if($product == $productInList->productInfo){
        $countOfTheSameProduct += 1;
      }

      if($countOfTheSameProduct > 1){
        return true;
      }
    }
    return false;
  }

  private function calculateTotalAmount()
  {
    $totalAmount = 0;
    foreach($this->productWithQuantityList as $product)
    {
      $totalAmount += $product->amount();
    }
    $this->totalAmount = $totalAmount;
  }

	public function allProducts(): array 
  {
		return $this->productWithQuantityList;
	}

  public function totalAmount(): float
  {
    $this->calculateTotalAmount();
    return $this->totalAmount;
  }
	
  /**
   * @return ProductWithQuantity[]
   */
	public function lastAddedProducts(): array 
  {
		return $this->lastAddedProductsList;
	}
	
   /**
   * @return ProductWithQuantity[]
   */
	public function lastRemovedProducts(): array 
  {
		return $this->lastRemovedProductsList;
	}

   /**
   * @return ProductWithQuantity[]
   */
	public function lastUpdatedProducts(): array 
  {
		return $this->lastUpdatedProductsList;
	}

  public function order(): Order
  {
    return $this->order;
  }
}
