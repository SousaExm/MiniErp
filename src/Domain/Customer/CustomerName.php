<?php

namespace MiniErp\Domain\Customer;

class CustomerName
{
  private string $name;
  public function __construct($name)
  {
    $this->isValidName($name);
  }

  private function isValidName(string $name)
  {
    $this->isEmpty($name);
    $this->isOnlyOneName($name);
    $this->tooShortName($name);
    $this->tooLongName($name);
    $this->name = $name;
  }

  private function isEmpty(string $name){
    $name = str_replace(' ', '', $name);
    $isEmpty = strlen($name) == 0;
    
    if($isEmpty){
      throw new \DomainException('O nome do cliente nao pode ser vazio');
    }
  }

  private function tooShortName(string $name){
    
    $isNametooShort = strlen($name) < 8;
    if($isNametooShort){
      throw new \DomainException('O nome completo nao pode conter menos que 8 caracteres');
    }
  }

  private function tooLongName(string $name){
    
    $isNametooShort = strlen($name) > 55;
    if($isNametooShort){
      throw new \DomainException('O nome completo nao pode conter mais que 55 caracteres');
    }
  }

  private function isOnlyOneName(string $name){
    $nameArray = explode(' ', $name);
    $isOnlyOneName = count($nameArray) <= 1;

    if($isOnlyOneName){
      throw new \DomainException('É necessário informar no mínimo nome e sobrenome para o cliente');
    }
  }

  public function __toString()
  {
    return $this->name;
  }
}