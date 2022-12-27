<?php

use MiniErp\Domain\Common\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{

  /**
   * @dataProvider invalidEmailsProvider
   */
  public function testThrowsExceptionInvalidEmail($email, $message)
  { 
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage($message);
    new Email($email);
  }

  public function invalidEmailsProvider()
  {
    return [
      'Empty email' => ['', 'Por favor informe um e-mail válido'],
      'Without at symbol' => ['teste.teste.com', 'Por favor informe um e-mail válido'],
      'Without domain' => ['teste.teste@.com', 'Por favor informe um e-mail válido'],
      'Without username' => ['@teste.com', 'Por favor informe um e-mail válido'],
      'Too long email' => ['123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345@example.com', 'Por favor informe um e-mail válido']
    ];
  }
}
