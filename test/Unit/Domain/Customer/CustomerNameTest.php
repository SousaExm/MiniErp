<?php

use MiniErp\Domain\Customer\CustomerName;
use PHPUnit\Framework\TestCase;

class CustomerNameTest extends TestCase
{

  /**
   * @dataProvider invalidNameProvider
   */
  public function testThrowsExceptionInvalidName($name, $errorMessage)
  {
    $this->expectException(DomainException::class);
    $this->expectExceptionMessage($errorMessage);
    new CustomerName($name);
  }

  public function testValidName()
  {
    $name = new CustomerName('Teste Teste de Teste');
    $this->assertEquals('Teste Teste de Teste', $name);
  }

  public function invalidNameProvider()
  {
    return [
      "Empty name" => ['', 'O nome do cliente nao pode ser vazio'],
      "Only one name" => ['teste', 'É necessário informar no mínimo nome e sobrenome para o cliente'],
      "Too short name" => ['te mac', 'O nome completo nao pode conter menos que 8 caracteres'],
      "Too long name" => ['Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo cursus magna', 'O nome completo nao pode conter mais que 55 caracteres']
    ];
  }

}