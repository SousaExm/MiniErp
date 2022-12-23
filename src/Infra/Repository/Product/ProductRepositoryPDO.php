<?php

namespace MiniErp\Infra\Product\Repository;
use MiniErp\Domain\Product\Product;
use MiniErp\Domain\Repository\Product\ProductRepository;
use PDO;

class ProductRepositoryPDO implements ProductRepository 
{
	private PDO $pdo;
	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	/**
	 * @return Product[]
	 */
	public function allProducts(): array 
  {
		$query = 'SELECT * from product';
		$stmt = $this->pdo->prepare($query);
		$stmt->execute();
		$productsDataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $this->hydrateProductList($productsDataList);
	}

	/**
	 * @return Product[]
	 */
	private function hydrateProductList(array $productsDataList): array
	{
		$productsList = [];
		foreach($productsDataList as $productData)
		{
			$productsList[] = $this->hydrateProduct($productData);
		}
		return $productsList;
	}

	private function hydrateProduct($productData): Product
	{
		$product = new Product(
			$productData['name'],
			$productData['desription'],
			$productData['amount'],
			$productData['unitMeasurement'],
			$productData['status'],
			$productData['id']
		);
		return $product;
	}
	
	public function getProductById(string $uuid): Product | false
  {
		$query = 'SELECT * from product WHERE id = :id';
		$stmt = $this->pdo->prepare($query);
		$stmt->execute([
			':id' => $uuid
		]);
		
		$productData = $stmt->fetch(PDO::FETCH_ASSOC);	
		if($productData == false){
			return false;
		}

		return $this->hydrateProduct($productData);
	}
	
	public function save(Product $product): Product 
  {
		if(!$this->getProductById($product->uuid())){
			return $this->insert($product);
		}

		return $this->update($product);
	}

	private function insert(Product $product): Product
	{
		$product->generateUuid();
		$query = 'INSERT INTO product
							values (:id, :name, :description, :amount, :status, :unitMeasurement)';
		
		$stmt = $this->pdo->prepare($query);
		$stmt->execute([
			':id' => $product->uuid(),
			':name' => $product->name(),
			':description' => $product->description(),
			':amount' => $product->amount(),
			':status' => $product->isActive(),
			':unitMeasurement' => $product->unitMeasurement(),
		]);
		
		return $this->getProductById($product->uuid());
	}

	private function update(Product $product)
	{
		if($product->uuid() == null){
			throw new \InvalidArgumentException('Para atualizar um produto é necessário informar o seu ID');
		}

		$query = 'UPDATE product
							SET name = :name,
									description = :description, 
									amount = :amount, 
									unitMeasurement = :unitMeasurement, 
									status = :status
							WHERE id = :id
							';
		
		$stmt = $this->pdo->prepare($query);
		$stmt->execute([
			':id' => $product->uuid(),
			':name' => $product->name(),
			':description' => $product->description(),
			':amount' => $product->amount(),
			':status' => $product->isActive(),
			':unitMeasurement' => $product->unitMeasurement(),
		]);

		return $this->getProductById($product->uuid());
	}
	
	public function remove(Product $product): void
  {
		if($product->uuid() == null){
			throw new \InvalidArgumentException('Para remover um produto é necessário informar o seu ID');
		}

		$query = 'DELETE from product WHERE id = :id';
		$stmt = $this->pdo->prepare($query);
		$stmt->execute([
			':id' => $product->uuid()
		]);
		
	}
}