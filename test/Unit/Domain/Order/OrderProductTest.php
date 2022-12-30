<?php
use MiniErp\Domain\Order\OrderProduct;
use PHPUnit\Framework\TestCase;


class OrderProductTest extends TestCase
{
  public function testProductWithInvalidQuantity() 
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Nao é permitido ter um produto com sua quantidade negativa ou zerada.');

    new OrderProduct('testId', -3);
  } 

  public function testProductWithInvalidPrice() 
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Um produto nao pode ter valor zerado ou negativo.');

    $orderProduct = new OrderProduct('testId', 3);
    $orderProduct->priceAtTimeOfOrder(-400);
  }

  public function testCalculateTotal()
  {
    $orderProduct1 = new OrderProduct('testId', 2);
    $orderProduct1->priceAtTimeOfOrder(2.5);

    $this->assertEquals(5, $orderProduct1->totalPrice());
  }

  public function testCalculateTotalWithNoPriceProduct()
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Nao é possível calcular o total de um produto ou pedido sem definir sem definir o preço dos itens.');

    $orderProduct = new OrderProduct('testId', 2);
    $orderProduct->totalPrice();  
  }
}
