<?php

use MiniErp\Domain\Product\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{

  /**
   * @dataProvider incorrectArgumentsProvider
   */
  public function testInvalidArguments($name, $description, $price, $unit, $isActive, $errorMsg)
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage($errorMsg);

    new Product($name, $description, $price, $unit,  $isActive);
  }

  public function testInvalidUuid()
  {
    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('O id do produto é inválido.');

    new Product('Produto', 'Produto incrível para qualquer ocasião. Faça a diferença com ele!', 45.5, 'UN', true, '015015');
  }
  
  public function testGenerateUuid()
  {
    $product = new Product('Produto', 'Produto incrível para qualquer ocasião. Faça a diferença com ele!', 45.5, 'UN', true);

    $this->assertEquals(13, strlen($product->uuid()));
    $this->assertIsString($product->uuid());
  }

  public function testValidProduct()
  {
    $product = new Product('Produto', 'Produto incrível para qualquer ocasião. Faça a diferença com ele!', 45.5, 'UN', true);

    $this->assertEquals('Produto', $product->name()); 
    $this->assertEquals('Produto incrível para qualquer ocasião. Faça a diferença com ele!', $product->description()); 
    $this->assertEquals(45.5, $product->amount()); 
    $this->assertEquals('UN', $product->unitMeasurement()); 
    $this->assertEquals(true, $product->isActive()); 
  }

  public function incorrectArgumentsProvider()
  {
    return [
      'Too short name' => ['ab', 'Produto incrível para qualquer ocasião. Faça a diferença com ele!', 45.50, 'UN', true, 'O nome do produto deve conter no mínimo 3 caracteres'],
      'Too long name' => ['ab Produto incrível para qualquer ocasião Faça a diferença', 'Produto incrível para qualquer ocasião. Faça a diferença com ele!', 45.50, 'UN', true, 'O nome do produto deve conter no máximo 55 caracteres'],
      'Too short description' => ['Produto', 'Produto incrível para qualquer ocasião.', 45.50, 'UN', true, 'A descricao do produto deve conter no mínimo 55 caracteres'],
      'Too long description' => ['Produto', 'Adquira o nosso produto genérico e transforme qualquer ocasião em algo ainda mais especial. Com design moderno e feito com materiais de qualidade, ele é a escolha perfeita para uso pessoal ou como presente. Além disso, é fácil de usar e se adapta a qualquer situação.', 45.50, 'UN', true, 'A descricao do produto deve conter no máximo 255 caracteres'],
      'Amount equal 0' =>  ['Produto', 'Produto incrível para qualquer ocasião. Faça a diferença com ele!', 0, 'UN', true, 'O preço nao pode ser negativo ou nulo'],
      'Amount < 0' =>  ['Produto', 'Produto incrível para qualquer ocasião. Faça a diferença com ele!', -10, 'UN', true, 'O preço nao pode ser negativo ou nulo'],
      'Too short UM' =>  ['Produto', 'Produto incrível para qualquer ocasião. Faça a diferença com ele!', 45.50, '', true, 'Unidade de medida inválida'],
      'Too long UM' =>  ['Produto', 'Produto incrível para qualquer ocasião. Faça a diferença com ele!', 45.50, 'UNIT1', true, 'Unidade de medida inválida']
    ];
  }
}