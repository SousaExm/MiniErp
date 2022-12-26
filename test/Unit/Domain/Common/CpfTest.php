<?php

use MiniErp\Domain\Common\Cpf;
use PHPUnit\Framework\TestCase;

class CpfTest extends TestCase
{
  public function testCheckRemovesDotsAndDashes()
  {
    $cpf = new Cpf('476.042.198-02');
    $this->assertEquals('47604219802', $cpf);
  }

  /**
   * @dataProvider incorrectSizeProvider
   */
  public function testIncorrectSizeThrowsException(string $cpf, $message )
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage($message);
    new Cpf($cpf);
  }

  public function testAllTheNumbersAreEqualThrowsException()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Todos os digitos do CPF sao iguais');
    new Cpf('11111111111'); 
  }

  public function testDoesNotMatchWithCountryValidation()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('O CPF possuí numeracao inválida');
    new Cpf('47604219803'); 
  }

  public function testValidCpf()
  {
    $cpf = new Cpf('47604219802');
    $this->assertEquals('47604219802', $cpf);
  }

  public function incorrectSizeProvider()
  {
    return [
      'Too Long' => ['450450450455', 'O CPF informado possuí tamanho inválido'],
      'Too short' => ['4504504504', 'O CPF informado possuí tamanho inválido']
    ];
  }
}