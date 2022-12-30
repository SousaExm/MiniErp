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
    $this->validateUuid();
    $this->isActive = $isActive;
  }

  private function validateUuid()
  {
    if($this->uuid === null){
      $this->uuid = uniqid();
    }

    if(strlen($this->uuid) !== 13){
      throw new \DomainException('O id do produto é inválido.');
    }
  }
  private function validateName(string $name)
  {
    if(strlen($name) < 3){
      throw new \InvalidArgumentException('O nome do produto deve conter no mínimo 3 caracteres');
    }
    if(strlen($name) > 55){
      throw new \InvalidArgumentException('O nome do produto deve conter no máximo 55 caracteres');
    }
    $this->name = $name;
  }

  private function validateDescription($description)
  {
    if(strlen($description) < 55){
      throw new \InvalidArgumentException('A descricao do produto deve conter no mínimo 55 caracteres');
    }
    if(strlen($description) > 255){
      throw new \InvalidArgumentException('A descricao do produto deve conter no máximo 255 caracteres');
    }

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
    if(strlen($unit) > 4 || strlen($unit) == 0){
      throw new \InvalidArgumentException('Unidade de medida inválida');
    }

    $this->unitMeasurement = $unit;
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
