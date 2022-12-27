<?php

namespace MiniErp\Domain\Repository\Order;

use MiniErp\Domain\Order\Order;
use MiniErp\Domain\Order\OrderWithProducts;

interface OrderProductsRepository 
{
  public function getProducts(Order $order): OrderWithProducts;
  public function save(OrderWithProducts $order): OrderWithProducts;
  public function removeAll(Order $order);
}