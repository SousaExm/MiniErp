<?php

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
    $this->tooShortName($name);
    $this->isOnlyOneName($name);
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
    
    $isNametooShort = strlen($name) < 3;
    if($isNametooShort){
      throw new \InvalidArgumentException();
    }
  }

  private function isOnlyOneName(string $name){
    $nameArray = explode(' ', $name);
    $isOnlyOneName = count($nameArray) < 1;

    if($isOnlyOneName){
      throw new \DomainException('É necessário informar no mínimo nome e sobrenome para o cliente');
    }
  }

  public function __toString()
  {
    return $this->name;
  }
}