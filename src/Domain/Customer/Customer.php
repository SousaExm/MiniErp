<?php

namespace MiniErp\Domain\Customer;

use DateTimeImmutable;
use MiniErp\Domain\Common\{
  Addressable,
  PhoneCallable,
  Cpf,
  Email,
  Phone,
  Address
};


class Customer implements Addressable, PhoneCallable
{
  private $uuid;
  private string $name;
  private Cpf $cpf;
  private Email $email;
  private DateTimeImmutable $birthDate;
  private Address | null $address = null;
  private array $phones;

  public function __construct(string $name, 
                              string $cpf, 
                              string $email, 
                              DateTimeImmutable $birthDate,
                              string | null $uuid = '')
  {
    $this->uuid = $uuid;
    $this->isValidName($name);
    $this->cpf = new Cpf($cpf);
    $this->email = new Email($email);
    $this->birthDate = $birthDate;
    $this->phones = [];
  } 

  private function isValidName(string $name)
  {
    $this->isEmpty($name);
    $this->tooShortName($name);
    $this->isOnlyOneName($name);
    $this->name = $name;
  }

  private function isEmpty(string $name){
    $name = str_replace(' ', '', $name);
    $isEmpty = strlen($name) == 0;
    
    if($isEmpty){
      throw new \InvalidArgumentException();
    }
  }

  private function tooShortName(string $name){
    
    $isNametooShort = strlen($name) < 3;
    if($isNametooShort){
      throw new \InvalidArgumentException();
    }
  }

  private function isOnlyOneName(string $name){
    $nameArray = explode(' ', $name);
    $isOnlyOneName = count($nameArray) < 1;

    if($isOnlyOneName){
      throw new \InvalidArgumentException();
    }
  }

  public function addPhone(string $areaCode, string $phoneNumber, bool $hasWhatsApp)
  {
    if(count($this->phones) >= 2){
      throw new \DomainException('Permitido no máximo 2 telefones por pessoa');
    }

    $this->phones[] = new Phone($areaCode, $phoneNumber, $hasWhatsApp);
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

  public function generateUuid()
  {
    if($this->uuid !== ''){
      throw new \DomainException('Voce só pode definir o ID uma única vez');
    }
    $this->uuid = uniqid();
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
    return $this->birthDate->format('d-m-Y');
  }

  /**
   *
   * @return Phone[]
   */
  public function phones(): array
  {
    return $this->phones;
  }

  public function address(): ?Address
  {
    return $this->address;
  }
}