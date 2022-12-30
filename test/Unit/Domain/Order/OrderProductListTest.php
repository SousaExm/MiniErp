<?php

use MiniErp\Domain\Order\OrderProductsList;
use PHPUnit\Framework\TestCase;

class OrderProductsListTest extends TestCase
{
  /**
   * @dataProvider orderProductProvider
   */
  public function testInsertOneProduct($data)
  {
    [$product1] = $data;
    $productsList = new OrderProductsList(...$product1);
    $productsList->addProduct(...$product1);

    $this->assertCount(1, $productsList->list());
    $this->assertEquals(3, $productsList->list()[0]->quantity());
  }

  /**
   * @dataProvider orderProductProvider
   */
  public function testUpdateQuantity($data)
  {
    [$product1, $product2, $product3, $product4] = $data;
    $productsList = new OrderProductsList(...$product1);

    $product1['quantity'] = 6;
    $productsList->addProduct(...$product1);
    $productsList->addProduct(...$product2);   
    $productsList->addProduct(...$product3);   
    $productsList->addProduct(...$product4);   

    $this->assertCount(4, $productsList->list());

    $this->assertEquals(6, $productsList->list()[0]->quantity());
    $this->assertEquals(2, $productsList->list()[1]->quantity());
    $this->assertEquals(1, $productsList->list()[2]->quantity());
    $this->assertEquals(10, $productsList->list()[3]->quantity());
  }

  /**
   * @dataProvider orderProductProvider
   */
  public function testRemoveOneInexistentProduct($data)
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('O produto nao foi encontrado na lista de produtos');

    [$product1, $product2] = $data;
    $productsList = new OrderProductsList(...$product1);
    $productsList->removeProduct($product2['productId']);
  }

  /**
   * @dataProvider orderProductProvider
   */
  public function testRemoveTheUniqueExistentProduct($data)
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Nao é permitido excluir todos os items de um pedido, para tal, exclua o pedido');

    [$product1] = $data;
    $productsList = new OrderProductsList(...$product1);
    $productsList->removeProduct($product1['productId']);
  }

  /**
   * @dataProvider orderProductProvider
   */
  public function testRemoveProducts($data)
  {
    [$product1, $product2, $product3, $product4] = $data;
    $productsList = new OrderProductsList(...$product1);

    $productsList->addProduct(...$product1);
    $productsList->addProduct(...$product2);   
    $productsList->addProduct(...$product3);   
    $productsList->addProduct(...$product4);

    $productsList->removeProduct($product2['productId']);

    $this->assertCount(3, $productsList->list());

    $this->assertEquals(3, $productsList->list()[0]->quantity());
    $this->assertEquals(1, $productsList->list()[1]->quantity());
    $this->assertEquals(10, $productsList->list()[2]->quantity());
  }

  /**
   * @dataProvider orderProductProvider
   */
  public function testCalculateTotalWithoutPrices($data)
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Nao é possível calcular o total de um produto ou pedido sem definir sem definir o preço dos itens.');

    [$product1, $product2, $product3, $product4] = $data;
    $productsList = new OrderProductsList(...$product1);

    $productsList->addProduct(...$product1);
    $productsList->addProduct(...$product2);   
    $productsList->addProduct(...$product3);   
    $productsList->addProduct(...$product4);

    $productsList->totalAmount();
  }

  public function orderProductProvider()
  {
    $product1 = array('productId' => '1', 'quantity' => 3);
    $product2 = array('productId' => '2', 'quantity' => 2);
    $product3 = array('productId' => '3', 'quantity' => 1);
    $product4 = array('productId' => '4', 'quantity' => 10);
    $data = [[$product1, $product2, $product3, $product4]];
    return ['Products' => $data];
  }
}