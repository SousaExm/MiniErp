<?php

namespace MiniErp\Domain\Common;

class Address
{
  private string $street;
  private string $number;
  private string $neighborhood;
  private string $city;
  private string $state;
  private string $cep;

  public function __construct(string $street, 
                              string $number, 
                              string $neighborhood, 
                              string $city, 
                              string $state, 
                              string $cep)
  {
    $this->setStreet($street);
    $this->setNumber($number);
    $this->setNeighborhood($neighborhood);
    $this->setCity($city);
    $this->setState($state);
    $this->setCep($cep);
  }

	public function setStreet(string $street): void {
    if(strlen($street) == 0){
      throw new \InvalidArgumentException('O nome da rua nao pode ser vazio');
    }
    if(strlen($street) > 55){
      throw new \InvalidArgumentException('O nome da rua nao possuir mais que 55 caracteres');
    }
		$this->street = $street;
	}

	public function setNumber(string $number): void 
  {
    if(strlen($number) == 0){
      throw new \InvalidArgumentException('O numero da rua nao pode ser vazio');
    }
    if(strlen($number) > 10){
      throw new \InvalidArgumentException('O numero da rua nao pode conter mais que 10 caracteres');
    }
		$this->number = $number;
	}
	
	public function setNeighborhood(string $neighborhood): void 
  {
    if(strlen($neighborhood) == 0){
      throw new \InvalidArgumentException('O nome do bairro nao pode ser vazio');
    }
    if(strlen($neighborhood) > 55){
      throw new \InvalidArgumentException('O nome do bairro nao possuir mais que 55 caracteres');
    }
		$this->neighborhood = $neighborhood;
	}
	
	public function setCity(string $city): void 
  {
    if(strlen($city) == 0){
      throw new \InvalidArgumentException('O nome da cidade nao pode ser vazio');
    }
    if(strlen($city) > 55){
      throw new \InvalidArgumentException('O nome da cidade nao possuir mais que 55 caracteres');
    } 
		$this->city = $city;
	}
	
	public function setState(string $state): void 
  {
    if(strlen($state) == 0){
      throw new \InvalidArgumentException('O nome do estado nao pode ser vazio');
    }
    if(strlen($state) > 20){
      throw new \InvalidArgumentException('O nome do estado nao possuir mais que 20 caracteres');
    } 
		$this->state = $state;
	}

	public function setCep(string $cep): void 
  {
    if(strlen($cep) != 8){
      throw new \InvalidArgumentException('O cep deve possuir apenas 8 caracteres');
    }
		$this->cep = $cep;
	}

	public function street(): string 
  {
		return $this->street;
	}

	public function number(): string 
  {
		return $this->number;
	}
	
	public function neighborhood(): string 
  {
		return $this->neighborhood;
	}
	
	public function city(): string 
  {
		return $this->city;
	}
	
	public function state(): string 
  {
		return $this->state;
	}
	
	public function cep(): string 
  {
		return $this->cep;
	}
	
}