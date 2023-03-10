<?php

namespace MiniErp\Domain\Customer;

use MiniErp\Domain\Customer\CustomerName;
use DateTimeImmutable;
use MiniErp\Domain\Common\{
  Addressable,
  PhoneCallable,
  Cpf,
  Email,
  Address,
  PhonesList
};

class Customer implements Addressable, PhoneCallable
{
  private $uuid;
  private CustomerName $name;
  private Cpf $cpf;
  private Email $email;
  private DateTimeImmutable $birthDate;
  private Address | null $address = null;
  private CustomerPhonesList $phonesList;

  public function __construct(string $name, 
                              string $cpf, 
                              string $email, 
                              DateTimeImmutable $birthDate,
                              string $uuid = '')
  {
    $this->name =  new CustomerName($name);
    $this->cpf = new Cpf($cpf);
    $this->email = new Email($email);
    $this->birthDate = $birthDate;
    $this->phonesList = new CustomerPhonesList();
    $this->uuid = $uuid;

    if($uuid == ''){
      $this->uuid = uniqid();
    }

    if(strlen($this->uuid) !== 13){
      throw new \DomainException('O id do cliente informado é inválido');
    }
  } 

  public function addAddress(string $street, 
                            string $number, 
                            string $neighborhood, 
                            string $city, 
                            string $state, 
                            string $cep)
  {
    $this->address = new Address($street, $number, $neighborhood, $city, $state, $cep);
  }

  public function uuid(): string
  { 
    return $this->uuid;
  }

  public function name(): string
  {
    return $this->name;
  }

  public function cpf(): string
  {
    return $this->cpf;
  }

  public function email(): string
  {
    return $this->email;
  }
  
  public function birthDate(): string
  {
    return $this->birthDate->format('Y-m-d');
  }

  public function phonesList(): PhonesList
  {
    return $this->phonesList;
  }

  public function address(): ?Address
  {
    return $this->address;
  }
}