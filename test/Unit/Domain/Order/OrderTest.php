<?php
use MiniErp\Domain\Order\Order;
use MiniErp\Domain\Order\OrderProductsList;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
  public function testOrderWithInvalidId()
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('O id do pedido informado é inválido');

    $orderProductsList = new OrderProductsList('1', 1);
    $orderProductsList->addProduct('2', 1);
    $orderProductsList->addProduct('3', 1);
    $orderProductsList->addProduct('4', 1);

    new Order('1234', 'teste', new DateTimeImmutable('2022-12-30'), $orderProductsList, '123');
  }

  public function testOrderCalculateTotalWithoutPrice()
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Nao é possível calcular o total de um produto ou pedido sem definir sem definir o preço dos itens.');

    $orderProductsList = new OrderProductsList('1', 1);
    $orderProductsList->addProduct('2', 1);
    $orderProductsList->addProduct('3', 1);
    $orderProductsList->addProduct('4', 1);

    $order = new Order('1234', 'teste', new DateTimeImmutable('2022-12-30'), $orderProductsList);
    $order = $order->amount();
  }

  public function testOrderCalculateTotalPrice()
  {
    $orderProductsList = new OrderProductsList('1', 1);
    $orderProductsList->addProduct('2', 1);
    $orderProductsList->addProduct('3', 1);
    $orderProductsList->addProduct('4', 1);

    $order = new Order('1234', 'teste', new DateTimeImmutable('2022-12-30'), $orderProductsList);
    $orderProducts = $order->productsList();

    $orderProducts->list()[0]->priceAtTimeOfOrder(1);
    $orderProducts->list()[1]->priceAtTimeOfOrder(1);
    $orderProducts->list()[2]->priceAtTimeOfOrder(1);
    $orderProducts->list()[3]->priceAtTimeOfOrder(55);

    $this->assertEquals(58, $order->amount());
  }
}