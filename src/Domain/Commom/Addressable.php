<?php

namespace MiniErp\Domain;

use MiniErp\Domain\Customer\Address;

interface Addressable
{
  public function uuid(): string | null;
  public function address(): Address | null;
  public function addAddress( string $street, 
                              string $number, 
                              string $neighborhood, 
                              string $city, 
                              string $state, 
                              string $cep);
}