<?php

namespace MiniErp\Infra\Repository\Order;

use MiniErp\Domain\Customer\Customer;
use MiniErp\Domain\Order\Order;
use MiniErp\Domain\Order\OrderWithProducts;
use MiniErp\Domain\Repository\CustomerRepository;
use MiniErp\Domain\Repository\Order\OrderProductsRepository;
use MiniErp\Domain\Repository\Order\OrderRepository;
use PDO;


class OrderRepositoryPDO implements OrderRepository
{
  private PDO $pdo;
  private CustomerRepository $customerRepository;
  private OrderProductsRepository $orderProductsRepository;
  private int $limit = 10;
  private array $ordenedBy = [''];
  private int $ofsset = 0;

  public function __construct(PDO $pdo, CustomerRepository $customerRepository, OrderProductsRepository $orderProductsRepository)
  { 
    $this->pdo = $pdo;
    $this->customerRepository = $customerRepository;
    $this->orderProductsRepository = $orderProductsRepository;
  }
  
	/**
	 * @return Order[]
	 */
	public function allOrders(): array 
  {
    $ordenedBy = implode(', ', $this->ordenedBy);

    $query = 'SELECT * from customerOrder          
              LIMIT :limitOfResults
              OFSSET' . $this->ofsset . $ordenedBy;
              
              
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':limitOfResults', $this->limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $ordersDataList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $this->hydrateOrdersList($ordersDataList);    
	}
	
  private function hydrateOrdersList($ordersDataList)
  {
    $ordersList = [];
    foreach($ordersDataList as $orderData)
    {
      $ordersOwner = $this->customerRepository->getCustomerById($orderData['customerId']);
      $ordersList[] = $this->hydrateOrder($orderData, $ordersOwner);
    }
    return $ordersList;
  }

  private function hydrateOrder(array $orderData, Customer $owner): Order
  {
    $order = new Order(
      $owner,
      $orderData['status'],
      $orderData['createAt'],
      $orderData['id'],
      $orderData['amount']
    );
    return $order;
  }


	public function save(OrderWithProducts $orderWithProducts): OrderWithProducts 
  {
    if($orderWithProducts->order->uuid() === null){
      $orderWithProducts->order->generateUuid();
      return $this->insert($orderWithProducts);
    }

    return $this->update($orderWithProducts); 
	}

  private function insert(OrderWithProducts $orderWithProducts): OrderWithProducts
  {
    $order = $orderWithProducts->order();
    $customer = $orderWithProducts->order()->owner();

    $query = 'INSERT INTO customerOrder
              values(:id, :customerId, :status, :createdAt, :amount)';
    
    $stmt = $this->pdo->prepare($query);

    $stmt->execute([
      ':id' => $order->uuid(),
      ':customerId' => $customer->uuid(), 
      ':status' => $order->status(), 
      ':createdAt' => $order->createdAt(), 
      ':amount' => $orderWithProducts->totalAmount()
    ]);     
    return $this->orderProductsRepository->save($orderWithProducts);
  }

  private function update(OrderWithProducts $orderWithProducts): OrderWithProducts
  {
    $order = $orderWithProducts->order();

    $query = 'UPDATE customerOrder
              SET status = :status, amount = :amount
              WHERE id = :id';
    
    $stmt = $this->pdo->prepare($query);

    $stmt->execute([
      ':id' => $order->uuid(),
      ':status' => $order->status(), 
      ':amount' => $orderWithProducts->totalAmount()
    ]);     
    return $this->orderProductsRepository->save($orderWithProducts);
  }

	public function remove(Order $order): void 
  {
    $query = 'DELETE FROM customerOrder
              WHERE id = :orderId';

    $stmt = $this->pdo->prepare($query);
    $stmt->execute([
      ':orderId' => $order->uuid()
    ]);
	}

	public function getOrderById(string $id): OrderWithProducts 
  {
    $query = 'SELECT * FROM customerOrder
              WHERE id = :id';

    $stmt = $this->pdo->prepare($query);
    
    $stmt->execute([
      ':id' => $id
    ]);

    $orderData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(count($orderData) == 0){
      throw new \InvalidArgumentException('Nenhum pedido foi encontrado para o ID informado.');
    }

    $ordersOwner = $this->customerRepository->getCustomerById($orderData['customerId']);
    $order = $this->hydrateOrder($orderData, $ordersOwner);
    
    return $this->orderProductsRepository->getProducts($order);
	}
	
	public function ordersByCustomer(Customer $customer): array 
  {
    $query = 'SELECT * FROM customerOrder
              WHERE customerId = :customerId
              LIMIT :limitOfResults
              OFSSET' .$this->ofsset;

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':limitOfResults', $this->limit, PDO::PARAM_INT);

    $stmt->execute([
      ':customerId' => $customer->uuid(),
    ]);

    $ordersDataList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($ordersDataList) == 0){
      throw new \InvalidArgumentException('Nenhum pedido foi encontrado para o cliente informado.');
    }
    
    $ordersList = [];
    foreach($ordersDataList as $orderData)
    {
      $ordersOwner = $this->customerRepository->getCustomerById($orderData['customerId']);
      $ordersList[] = $this->hydrateOrder($orderData, $ordersOwner);
    }
    return $ordersList;
	}
	
	public function addOrdantionBy(string $column = null, string $direction): void 
  {
    $possibilitiesForColumns = ['customer', 'createdAt', 'status', 'amount'];
    $possiblitiesForDirection = ['DESC', 'ASC'];
    $actualOrdenation = implode(' ', $this->ordenedBy);

    $newOrdenation = !strpos($actualOrdenation, $column);
    $validColumn = in_array($column, $possibilitiesForColumns);
    $validDirection = in_array($direction, $possiblitiesForDirection);

    if(!$validColumn || !$validDirection || !$newOrdenation){
      throw new \InvalidArgumentException('Um dos campos ordenados para pedidos nao está correto ou é repetido');
    }

    $this->ordenedBy[0] = 'ORDER BY';
    $this->ordenedBy[] = $column.' '.$direction;
	}

  public function setLimitOfResults(int $limit): void
  {
    if($limit <= 0){
      throw new \InvalidArgumentException('O limite de resultadaos nao pode ser negativo ou 0');
    }
    $this->limit = $limit;
  }

  public function setOfsset(int $ofsset): void
  {
    if($ofsset < 0){
      throw new \InvalidArgumentException('O valor de inicio de nao pode ser nulo');
    }
    $this->ofsset = $ofsset;
  }
}