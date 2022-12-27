<?php

use MiniErp\Domain\Common\Phone;
use PHPUnit\Framework\TestCase;

class PhoneTest extends TestCase
{

  public function testCorrectPhone()
  {
    $phone = new Phone('011', '992324545', true);
    $this->assertEquals($phone->areaCode(), '011');
    $this->assertEquals($phone->number(), '992324545');
  }

  /**
   * @dataProvider incorrectAreaCodeProvider
   */
  public function testThrowsExceptionInvalidPhoneParams($areaCode, $phoneNumber, $errorMessage)
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage($errorMessage);
    new Phone($areaCode , $phoneNumber, true);
  }

  public function incorrectAreaCodeProvider()
  {
    return [
      'Too short ddd' => ['02', '44444545', 'O DDD deve ser informado com 3 dígitos'],
      'Too long ddd' => ['0231','44444545', 'O DDD deve ser informado com 3 dígitos'],
      'Uncovered ddd' => ['001', '44444545', 'O DDD informado nao faz parte da cobertura nacional'],
      'Too short number' => ['011', '444545','O número de telefone pode conter no máximo 9 digitos e no mínimo 8'],
      'Too long number' => ['011', '444545444545','O número de telefone pode conter no máximo 9 digitos e no mínimo 8'],
      'Phone numbers are equals' => ['011', '44444444','Todos os digitos do número de telefone sao iguais'],
      'Cell phone number does not start with 9' => ['011', '397470204','O telefone celular precisa começar com o digito 9'],
    ];
  }
}