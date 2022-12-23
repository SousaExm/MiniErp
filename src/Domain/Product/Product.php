<?php

namespace MiniErp\Domain\Product;

class Product
{
  private string | null $uuid;
  private string $name;
  private string $description;
  private float $amount;
  private string $unitMeasurement;
  private bool $isActive;

  public function __construct(string $name, string $description, float $amount, string $unitMeasurement, bool $isActive, string | null $uuid = null)
  {
    $this->uuid = $uuid;
    $this->validateName($name);
    $this->validateDescription($description);
    $this->validateAmount($amount);
    $this->validateUnitMeasurement($unitMeasurement);
    $this->isActive = $isActive;
  }

  private function validateName(string $name)
  {
    $this->isEmpty($name);
    $this->minAndMaxLength($name, 3, 55);
    $this->name = $name;
  }

  private function validateDescription($description)
  {
    $this->isEmpty($description);
    $this->minAndMaxLength($description, 55, 255);
    $this->description = $description;
  }

  private function validateAmount(float $amount)
  {
    if($amount <= 0){
      throw new \InvalidArgumentException('O preço nao pode ser negativo ou nulo');
    }
    $this->amount = $amount;
  }

  private function validateUnitMeasurement($unit)
  {
    $this->isEmpty($unit);
    $this->minAndMaxLength($unit, 2, 2);
    $this->unitMeasurement = $unit;
  }

  private function isEmpty(string $text){
    $name = str_replace(' ', '', $text);
    $isEmpty = strlen($name) == 0;
    
    if($isEmpty){
      throw new \InvalidArgumentException('Os campos nao podem ser vazios');
    }
  }

  private function minAndMaxLength(string $text, int $minChar, int $maxChar){
    $isTextTooLong = strlen($text) > $minChar;
    $isTextTooShort = strlen($text) < $maxChar;
    if($isTextTooShort){
      throw new \InvalidArgumentException("$text é muito curto para o campo informado");
    }

    if($isTextTooLong){
      throw new \InvalidArgumentException("$text é muito curto para o campo informado");
    }
  }

  public function generateUuid()
  {
    if($this->uuid !== null){
      throw new \DomainException('Voce só pode definir o ID uma única vez');
    }
    $this->uuid = uniqid();  
  }

	public function uuid(): string {
		return $this->uuid;
	}
	
	public function name(): string {
		return $this->name;
	}

	public function description(): string {
		return $this->description;
	}

	public function amount(): float {
		return $this->amount;
	}
	
	public function unitMeasurement(): string {
		return $this->unitMeasurement;
	}
	
	public function isActive(): bool 
  {
		return $this->isActive;
	}
}
