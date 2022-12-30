<?php

namespace MiniErp\Domain\Order;

use DateTimeImmutable;

class Order
{
  private string $uuid;
  private string $customerId;
  private string $status;
  private DateTimeImmutable $createdAt;
  private OrderProductsList $productsList;

  public function __construct(string $customerId, string $status, DateTimeImmutable $createdAt, string | null $uuid = null, OrderProductsList $productsList)
  {
    $this->uuid = $uuid;
    $this->customerId = $customerId;
    $this->status = $status; 
    $this->createdAt = $createdAt;
    $this->productsList = $productsList;
    $this->validateUuid();
  }

  private function validateUuid()
  {
    if($this->uuid === null){
      $this->uuid = uniqid();
    }

    if(strlen($this->uuid) < 13){
      new \DomainException('O id do pedido informado é inválido');
    }
  }

	public function uuid(): string | null
  {
		return $this->uuid;
	}

	public function customerId(): string 
  {
		return $this->customerId;
	}
	
	public function status(): string {
		return $this->status;
	}
	
	public function createdAt(): string 
  {
		return $this->createdAt->format('Y-m-d');
	}

  public function amount(): float 
  {
    return $this->productsList->totalAmount();
  }

  public function productsList(): OrderProductsList
  {
    return $this->productsList;
  }
}

