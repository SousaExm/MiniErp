<?php

namespace MiniErp\Domain\Common;

class Phone 
{
  private string $areaCode;
  private string $phoneNumber;
  private bool $hasWhatsApp;

  public function __construct(string $areaCode, string $phoneNumber, bool $hasWhatsApp)
  {
    $this->setAreaCode($areaCode);
    $this->setPhoneNumber($phoneNumber);
    $this->hasWhatsApp = $hasWhatsApp;
  }

  private function setAreaCode(string $areaCode) 
  {
    $this->validateAreaCode($areaCode); 
    $this->areaCode = $areaCode;
  }

  private function validateAreaCode($areaCode)
  {
    $listOfCorretsAreaCodes = [68, 82, 96, 92, 97, 71, 73, 74, 75, 77, 85, 88, 61, 27, 28, 62, 64, 98, 99, 65, 66, 67, 31, 32, 33, 34, 35, 37, 38, 91, 93, 94, 83, 41, 42, 43, 44, 45, 46, 81, 87, 86, 89, 21, 22, 24, 84, 51, 53, 54, 55, 69, 95, 47, 48, 49, 11, 12, 13, 14, 15, 16, 17, 18, 19,79,63];
    
    if(strlen($areaCode) !== 3){
      throw new \InvalidArgumentException('O DDD deve ser informado com 3 dígitos');
    }

    if($areaCode[0] == 0){
      $areaCode = substr($areaCode, 1);
    }

    if(!in_array($areaCode, $listOfCorretsAreaCodes)){
      throw new \InvalidArgumentException('O DDD informado nao faz parte da cobertura nacional');
    }
  }

  private function setPhoneNumber(string $phoneNumber) 
  {
    $this->validatePhoneNumber($phoneNumber);    
    $this->phoneNumber = $phoneNumber;
  }

  private function validatePhoneNumber(string $phoneNumber)
  {
    $numberOfDigits = strlen($phoneNumber);

    if($numberOfDigits < 8 || $numberOfDigits > 9){
      throw new \InvalidArgumentException('O número de telefone pode conter no máximo 9 digitos e no mínimo 8');
    } 

    if(substr_count($phoneNumber, $phoneNumber[0]) == $numberOfDigits) {
      throw new \InvalidArgumentException('Todos os digitos do número de telefone sao iguais');
    }    

    if($numberOfDigits == 9 && $phoneNumber[0] !==  '9'){
      throw new \InvalidArgumentException('O telefone celular precisa começar com o digito 9'); 
    }
  }

  public function areaCode(): string 
  {
    return $this->areaCode;
  }

  public function number(): string 
  {
    return $this->phoneNumber;
  }

  public function hasWhatsApp(): bool 
  {
    return $this->hasWhatsApp;
  }
}