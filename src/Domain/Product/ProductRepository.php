<?php

namespace MiniErp\Domain\Repository\Product;
use MiniErp\Domain\Product\Product;

interface ProductRepository
{
  /**
   * @return Product[]
   */
  public function allProducts(): array;
  public function getProductById(string $uuid): Product | null;
  public function save(Product $product): void;
  public function remove(Product $product): void;
}