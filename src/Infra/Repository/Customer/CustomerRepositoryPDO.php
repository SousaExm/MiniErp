<?php

namespace MiniErp\Infra\Repository\Customer;

use DateTimeImmutable;
use MiniErp\Domain\Customer\Customer;
use MiniErp\Domain\Repository\Common\AddressRepository;
use MiniErp\Domain\Repository\Common\PhoneRepository;
use MiniErp\Domain\Repository\Customer\CustomerRepository;
use PDO;

class CustomerRepositoryPDO implements CustomerRepository
{
  private PDO $pdo;
  private AddressRepository $addressRepository;

  private PhoneRepository $phonesRepository;

  public function __construct(PDO $pdo, AddressRepository $addressRepository, PhoneRepository $phonesRepository)
  {
    $this->pdo = $pdo;
    $this->addressRepository = $addressRepository; 
    $this->phonesRepository = $phonesRepository;
  }
  
	/**
	 * @return Customer[]
	 */
	public function allCustomers(): array 
  {
    $query = 'SELECT * FROM customer';
    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    $customerDataList =  $stmt->fetchAll(PDO::FETCH_ASSOC);  
    return $this->hydrateCustomersList($customerDataList);
	}

  private function hydrateCustomersList(array $customerDataList): array
  {
    $customersList = [];  
    foreach($customerDataList as $customerData) {      
      $customersList[] = $this->hydrateCustomer($customerData);
    }
    return $customersList;
  }

  private function hydrateCustomer($customerData): Customer | null
  {   
    $customer = new Customer(
      $customerData['name'],
      $customerData['cpf'],
      $customerData['email'],
      new DateTimeImmutable($customerData['birthDate']),
      $customerData['id']
    );
      $customerWithPhone = $this->phonesRepository->getPhonesFor($customer);
      $customerWithPhoneAndAdress = $this->addressRepository->getAddressFor($customerWithPhone);

    return $customerWithPhoneAndAdress;
  }

  public function getCustomerById(string $id): Customer | false 
  {
    $query = 'SELECT * FROM customer
    WHERE id = :customerId';
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([
    ':customerId' => $id
    ]);

    $customerData = $stmt->fetch(PDO::FETCH_ASSOC);    
    if($customerData == false){
    return false;
    }

  return $this->hydrateCustomer($customerData);
	}
	
	public function getCustomerByCpf(string $cpf): Customer | false
  {
    $query = 'SELECT * FROM customer
              WHERE cpf = :customerCpf';
    
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([
      ':customerCpf' => $cpf
    ]);

    $customerData = $stmt->fetch(PDO::FETCH_ASSOC);    
    if($customerData == false){
      return false;
    }

    return $this->hydrateCustomer($customerData);
	}

  public function save(Customer $customer): Customer 
  {
    if(!$this->getCustomerByCpf($customer->cpf())){
      return $this->insert($customer);
    };

    return $this->update($customer);
	}
	
	public function remove(Customer $customer): void 
  {
    if($customer->uuid() === ''){
      throw new \InvalidArgumentException('Para excluir um usuário é necessário informar o seu ID');
    }

    $this->phonesRepository->remove($customer);
    $this->addressRepository->remove($customer);
    
    $query = 'DELETE from customer
              WHERE id = :customerUuid';
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([
      ':customerUuid' => $customer->uuid()
    ]);
	}

  private function insert(Customer $customer): Customer
  {
    $customer->generateUuid();
    $query = 'INSERT INTO customer
              values (:id, :cpf, :name, :email, :birthDate)';
              
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([
      ':id' => $customer->uuid(),
      ':name' => $customer->name(),
      ':cpf' => $customer->cpf(),
      ':email' => $customer->email(),
      ':birthDate' => $customer->birthDate()
    ]);

    $this->phonesRepository->save($customer);
    $this->addressRepository->save($customer);
    return $customer;
  }
  
  private function update(Customer $customer): Customer
  {
    if($customer->uuid() === ''){
      throw new \InvalidArgumentException('Cliente com CPF já cadastrado, para atualiza-lo é necessário informar o seu ID');
    }
    $query = 'UPDATE customer
              SET name = :name, email = :email
              WHERE id = :id';
              
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([
      ':id' => $customer->uuid(),
      ':name' => $customer->name(),
      ':email' => $customer->email()
    ]);

    $this->phonesRepository->save($customer);
    $this->addressRepository->save($customer);
    return $this->getCustomerByCpf($customer->cpf());
  }
}
