<?php

namespace MiniErp\Domain\Order;

use DateTimeImmutable;
use MiniErp\Domain\Customer\Customer;

class Order
{
  private string $uuid;
  private Customer $owner;
  private string $status;
  private DateTimeImmutable $createdAt;
  private float $amount;

  public function __construct(Customer $owner, string $status, DateTimeImmutable $createdAt, string | null $uuid = null, $amount = 0)
  {
    $this->owner = $owner;
    $this->status = $status; 
    $this->createdAt = $createdAt;
    $this->uuid = $uuid;
    $this->amount = $amount;
  }

  public function generateUuid()
  {
    if($this->uuid !== null){
      throw new \DomainException('Voce só pode definir o ID uma única vez');
    }
    $this->uuid = uniqid();  
  }

	public function uuid(): string | null
  {
		return $this->uuid;
	}

	public function owner(): Customer 
  {
		return $this->owner;
	}
	
	public function status(): string {
		return $this->status;
	}
	
	public function createdAt(): string 
  {
		return $this->createdAt->format('d-m-Y');
	}

  public function amount(): float 
  {
    return $this->amount;
  }
}

