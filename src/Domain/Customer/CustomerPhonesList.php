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
  }

  public function addPhone(Phone $phone): void
  {
    $this->maxPhones();

    if(!$this->phoneExistsIn(...$this->phones, $phone)){
      throw new \DomainException('Nao é possível adicionar dois telefones com o mesmo número');
    }

    $this->phones[] = $phone; 
  }

  public function removePhone(string $phone): void
  {
    $phoneKey = $this->phoneExistsIn(...$this->phones, $phone);

    if($phoneKey){
      throw new \DomainException('Nao foi encontrado telefone correspondente ao número informado.');
    }

    $this->lastRemovedPhones[] = $phone;
    unset($this->phones, $phoneKey);
  }

  public function updatePhone(string $oldPhone, Phone $newPhone): void
  {
    $this->removePhone($oldPhone);
    $this->addPhone($newPhone);
  }

  private function maxPhones()
  {
    if(count($this->phones) > $this->maxPhones){
      throw new \DomainException('Nao é possível possuir mais de dois telefones por cliente');
    }
  }

  private function phoneExistsIn(Phone ...$phonesList, $needle): bool | int
  {
    foreach($phonesList as $key => $phoneInList)
    {
      if($phoneInList->number() == $needle){
        return $key;
      };
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