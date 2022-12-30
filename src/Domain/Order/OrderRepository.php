<?php

namespace MiniErp\Domain\Repository\Order;

use MiniErp\Domain\Customer\CustomerRepository;
use MiniErp\Domain\Order\Order;
use MiniErp\Domain\Order\OrdersProductRepository;

abstract class OrderRepository
{
  /**
   * @return Order[]
   */
  public abstract function allOrders(): array;

  /**
   * @return Order[]
   */
  public abstract function ordersByCustomer(string $id): array | null;
  public abstract function findById(string $id): Order | null;
  protected abstract function insert(Order $order): void;
  protected abstract function update(Order $order): void;

  private CustomerRepository $customerRepository;
  private OrdersProductRepository $ordesProductRepository;

  public function __construct(CustomerRepository $customerRepository, OrdersProductRepository $ordesProductRepository)
  {
    $this->customerRepository = $customerRepository;
    $this->ordesProductRepository = $ordesProductRepository;
  }

  public function save(Order $order)
  {
    $this->checkCustomerExistis($order->customerId());
    
    if($this->findById($order->uuid()) === null){
      $this->insert($order);  
    }
    
    $this->ordesProductRepository->save($order->uuid(), $order->productsList());
    $this->update($order);
  }

  private function checkCustomerExistis($customerId)
  {
    if($this->customerRepository->findById($customerId) === null){
      throw new \DomainException('O cliente proprietário do pedido nao foi encontrado');
    }
  }
}