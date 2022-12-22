<?php

namespace MiniErp\Domain\Repository\Order;

use MiniErp\Domain\Customer\Customer;
use MiniErp\Domain\Order\Order;
use MiniErp\Domain\Order\OrderWithProducts;

interface OrderRepository
{
  /**
   * @return OrderWithProducts[]
   */
  public function allOrders(): array;
  public function save(OrderWithProducts $order): OrderWithProducts;
  public function remove(Order $order): void;
  
  public function getOrderById(string $id): OrderWithProducts;
  
  /**
   * @return OrderWithProducts[]
   */
  public function ordersByCustomer(Customer $customer): array;

  /**
   * @return OrderWithProducts[]
   */
  public function ordersSortedBy(string $sorterdBy = null): array;
}