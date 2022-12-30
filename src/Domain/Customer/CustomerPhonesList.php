<?php

namespace MiniErp\Domain\Customer;
use MiniErp\Domain\Common\Phone;
use MiniErp\Domain\Common\PhonesList;

class CustomerPhonesList implements PhonesList
{
  private array $phones;
  private array $lastRemovedPhones;
  private int $maxPhones = 2;

  public function __construct()
  {
    $this->phones = [];
    $this->lastRemovedPhones = [];
  }

  public function addPhone(string $areaCode, string $phoneNumber, bool $hasWhatsApp): void
  {
    $phone = new Phone($areaCode, $phoneNumber, $hasWhatsApp);
    $this->maxPhones();
    if($this->phoneExistsIn($phone->number(), ...$this->phones) !== false){
      throw new \DomainException('Nao é possível adicionar dois telefones com o mesmo número');
    }

    $this->phones[] = $phone; 
  }

  public function removePhone(string $phone): void
  {
    $phoneKey = $this->phoneExistsIn($phone, ...$this->phones);

    if($phoneKey === false && $phoneKey !== 0){
      throw new \DomainException('Nao foi encontrado telefone correspondente ao número informado');
    }

    $this->lastRemovedPhones[] = $this->phones[$phoneKey];
  
    unset($this->phones[$phoneKey]);
    
    if(!isset($this->phones)){
      $this->phones = [];
      return;
    }
    
    $this->phones = array_values($this->phones);
  }

  public function updatePhone(string $oldPhone,string $areaCode, string $phoneNumber, bool $hasWhatsApp): void
  {
    $this->removePhone($oldPhone);
    $this->addPhone($areaCode, $phoneNumber, $hasWhatsApp);
  }

  private function maxPhones()
  {
    if(count($this->phones) >= $this->maxPhones){
      throw new \DomainException('Nao é possível possuir mais de dois telefones por cliente');
    }
  }

  private function phoneExistsIn($needle, Phone ...$phonesList,): bool | int
  {
    foreach($phonesList as $key => $phoneInList)
    {
      if($phoneInList->number() == $needle){
        return $key;
      }
    }
    return false;
  }

  /**
   * @return Phone[]
   */
  public function phones(): array
  {
    return $this->phones;
  }

   /**
   * @return Phone[]
   */
  public function lastRemovedPhones(): array
  {
    return $this->lastRemovedPhones;
  }
}