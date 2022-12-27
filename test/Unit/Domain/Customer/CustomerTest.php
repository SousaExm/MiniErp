<?php

use MiniErp\Domain\Common\{Address, Cpf, Email};
use MiniErp\Domain\Customer\{Customer, CustomerPhonesList};
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
  public function testUuidNewCustomer()
  {
    $customer = new Customer('Teste Teste', new Cpf('53405309069'), new Email('teste@teste.com'), new DateTimeImmutable('26-02-1999'));

    $this->assertEquals(13, strlen($customer->uuid()));
  }

  public function testUuidIsNotMutableCustomer()
  {
    $uuid = uniqid();
    $customer = new Customer('Teste Teste', new Cpf('53405309069'), new Email('teste@teste.com'), new DateTimeImmutable('26-02-1999'), $uuid);

    $this->assertEquals($uuid, $customer->uuid());
  }

  public function testInvalidUuid()
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('O id do cliente informado é inválido');

    new Customer('Teste Teste', new Cpf('53405309069'), new Email('teste@teste.com'), new DateTimeImmutable('26-02-1999'), '321');
  }

  public function testCustomerGetters()
  {
    $customer = new Customer('Teste Teste', new Cpf('53405309069'), new Email('teste@teste.com'), new DateTimeImmutable('26-02-1999'));

    $this->assertEquals('Teste Teste', $customer->name());
    $this->assertEquals('53405309069', $customer->cpf());
    $this->assertEquals('teste@teste.com', $customer->email());
    $this->assertEquals('1999-02-26', $customer->birthDate());
    $this->assertInstanceOf(CustomerPhonesList::class, $customer->phonesList());
    $this->assertEmpty($customer->address());
  }

  public function testAddAddress()
  {
    $address = new Address('teste', '123', 'teste teste', 'teste', 'teste', '07000100');
    $customer = new Customer('Teste Teste', new Cpf('53405309069'), new Email('teste@teste.com'), new DateTimeImmutable('26-02-1999'));
    $customer->addAddress($address);

    $this->assertEquals($address, $customer->address());
  }

  public function testChangeAddress()
  {
    $address1 = new Address('teste', '123', 'teste teste', 'teste', 'teste', '07000100');
    $address2 = new Address('teste', '123', 'teste teste', 'teste', 'teste', '07000500');
    $customer = new Customer('Teste Teste', new Cpf('53405309069'), new Email('teste@teste.com'), new DateTimeImmutable('26-02-1999'));
    
    $customer->addAddress($address1);
    $customer->addAddress($address2);

    $this->assertNotEquals($address1, $customer->address());
    $this->assertEquals($address2, $customer->address());
  }
}
