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
    if(strlen($areaCode) !== 3){
      throw new \InvalidArgumentException('O DDD deve ser informado com 3 dígitos');
    }
    $this->areaCode = $areaCode;
  }

  private function setPhoneNumber(string $phoneNumber) 
  {
    if(strlen($phoneNumber) < 8 || strlen($phoneNumber) > 9){
      throw new \InvalidArgumentException('O número de telefone pode conter no máximo 9 digitos e no mínimo 8');
    }
    $this->phoneNumber = $phoneNumber;
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