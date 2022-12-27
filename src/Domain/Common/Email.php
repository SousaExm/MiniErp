<?php

namespace MiniErp\Domain\Common;

class Email 
{
  private string $email;
  public function __construct(string $address)
  {
    $this->validateEmail($address);
  }

  private function validateEmail(string $address): void 
  {
    if(!filter_var($address, FILTER_VALIDATE_EMAIL)){
      throw new \InvalidArgumentException('Por favor informe um e-mail válido');
    }

    $this->email = $address;  
  }

  public function __toString(): string
  {
    return $this->email;
  }
}
