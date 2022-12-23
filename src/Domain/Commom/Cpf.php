<?php

namespace MiniErp\Domain\Common;

class Cpf 
{
  private string $cpf;
  public function __construct(string $cpfNumber)
  {
    $this->validateCpf($cpfNumber);
  }

  public function validateCpf(string $cpf){
    $onlyNumberCpf = $this->getOnlyNumbers($cpf);
    $this->verifyLength($onlyNumberCpf);
    $this->areAllDigitsEquals($onlyNumberCpf);
    $this->rulesForValidCpf($onlyNumberCpf);
    $this->cpf = $onlyNumberCpf;  
  }

  private function getOnlyNumbers($cpf){
    $cpf = preg_replace('/[^0-9]/', "", $cpf);
    return $cpf;
  }

  private function verifyLength($cpf) {
    if (strlen($cpf) != 11) {
      throw new \InvalidArgumentException('O CPF informado possuí tamanho inválido');
    }
  }

  private function areAllDigitsEquals($cpf){
    if (preg_match('/([0-9])\1{10}/', $cpf)) {
      throw new \InvalidArgumentException('Todos os digitos do CPF sao iguais');
    }      
  }

  private function rulesForValidCpf($cpf){
    $number_quantity_to_loop = [9, 10];

    foreach ($number_quantity_to_loop as $item) {
      $sum = 0;
      $number_to_multiplicate = $item + 1;
    
      for ($index = 0; $index < $item; $index++) {
          $sum += $cpf[$index] * ($number_to_multiplicate--);
      }
      $result = (($sum * 10) % 11);

      if($result == 10){
        $result = 0;
      }
      
      if ($cpf[$item] != $result) {
        throw new \InvalidArgumentException('O CPF possuí numeracao inválida');
      }
    }
  }

  public function __toString(): string
  {
    return $this->cpf;
  }
}
