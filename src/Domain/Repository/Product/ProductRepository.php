<?php

namespace MiniErp\Domain\Repository\Product;
use MiniErp\Domain\Product\Product;

interface ProductRepository
{
  /**
   * @return Product[]
   */
  public function allProducts(): array;
  public function getProductById(string $uuid): Product | false;
  public function save(Product $product): Product;
  public function remove(Product $product): void;
}