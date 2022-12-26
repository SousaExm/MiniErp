<?php

use MiniErp\Domain\Common\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
  /**
   * @dataProvider returnsAddressWithEmptyParameters
   */
  public function testWhenAnEmptyParameterPassedToARequiredParameterThrowsException(
     string $street, string $number, string $neighborhood, string $city, string $state, string $cep, string $errorMsg
  )
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage($errorMsg);
    new Address($street, $number, $neighborhood, $city, $state, $cep);
  }

  /**
   * @dataProvider returnsAddressWithTooLongParameters
   */
  public function testWhenAnTooLongParameterPassedThrowsException(
     string $street, string $number, string $neighborhood, string $city, string $state, string $cep, string $errorMsg
  )
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage($errorMsg);
    new Address($street, $number, $neighborhood, $city, $state, $cep);
  }

  public function testCorrectlyAddress(){
    $address = new Address('Rua Teste Teste', '123456', 'Meu Bairro', 'Minha Cidade', 'Meu Estado', '12345678');
    $this->assertEquals('Rua Teste Teste', $address->street());
    $this->assertEquals('123456', $address->number());
    $this->assertEquals('Meu Bairro', $address->neighborhood());
    $this->assertEquals('Minha Cidade', $address->city());
    $this->assertEquals('Meu Estado', $address->state());
    $this->assertEquals('12345678', $address->cep());
  }


  public function returnsAddressWithEmptyParameters(){
    
    $withEmptyStreet = ['', '123', 'neighborhood','city', 'state', '01515151', 'O nome da rua nao pode ser vazio'];
    $withEmptyNumber = ['street', '', 'neighborhood','city', 'state', '01515151', 'O numero da rua nao pode ser vazio'];
    $withEmptyNeighborhood = ['street', '123', '','city', 'state', '01515151', 'O nome do bairro nao pode ser vazio'];
    $withEmptyCity = ['street', '123', 'neighborhood', '', 'state', '01515151', 'O nome da cidade nao pode ser vazio'];
    $withEmptyState = ['street', '123', 'neighborhood', 'City', '', '01515151', 'O nome do estado nao pode ser vazio'];
    $withEmptyCep = ['street', '123', 'neighborhood', 'City', 'state', '', 'O cep deve conter 8 caracteres'];
    return [
      $withEmptyStreet,
      $withEmptyNumber,
      $withEmptyNeighborhood,
      $withEmptyCity,
      $withEmptyState,
      $withEmptyCep
    ];
  }

  public function returnsAddressWithTooLongParameters(){
    
    $tooLongStreet = ['Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo cursus magna', '123', 'neighborhood','city', 'state', '01515151', 'O nome da rua nao pode conter mais que 55 caracteres'];

    $tooLongNumber = ['street', '12345678910', 'neighborhood','city', 'state', '01515151', 'O numero da rua nao pode conter mais que 10 caracteres'];

    $tooLongNeighborhood = ['street', '123', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo cursus magna','city', 'state', '01515151', 'O nome do bairro nao pode conter mais que 55 caracteres'];

    $tooLongCity = ['street', '123', 'neighborhood', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo cursus magna', 'state', '01515151', 'O nome da cidade nao pode conter mais que 55 caracteres'];

    $tooLongState = ['street', '123', 'neighborhood', 'City', 'Lorem ipsum dolor sit amet.', '01515151', 'O nome do estado nao pode conter mais que 20 caracteres'];

    $tooLongCep = ['street', '123', 'neighborhood', 'City', 'state', '123456789', 'O cep deve conter 8 caracteres'];
    
    return [
      $tooLongStreet,
      $tooLongNumber,
      $tooLongNeighborhood,
      $tooLongCity,
      $tooLongState,
      $tooLongCep
    ];
  }
}