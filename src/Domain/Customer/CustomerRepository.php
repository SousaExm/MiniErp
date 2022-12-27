<?php

namespace MiniErp\Domain\Customer;

use MiniErp\Domain\Common\{Cpf, Email};
use MiniErp\Domain\Customer\Customer;
use MiniErp\Domain\Repository\Common\AddressRepository;
use MiniErp\Domain\Repository\Common\PhoneRepository;

abstract class CustomerRepository 
{
  protected PhoneRepository $phoneRepository;
  protected AddressRepository $addressRepository;

  /**
   * @return Customer[]
   */
  abstract public function allCustomers(): array;
  abstract public function findById(string $id): Customer | null;
  abstract public function findByCpf(Cpf $cpf): Customer | null;
  abstract protected function findByEmail(Email $email): Customer | null;
  abstract protected function update(Customer $customer): void;
  abstract protected function add(Customer $customer): void;
  abstract protected function delete(Customer $customer): void;
  
  public function __construct(PhoneRepository $phoneRepository, AddressRepository $addressRepository){
    $this->phoneRepository = $phoneRepository;
    $this->addressRepository = $addressRepository;
  }
  
  private function chekIsNewCustomer(Customer $customer): bool
  {
    $customerOwnerOfThisId = $this->findById($customer->uuid());
    if ($customerOwnerOfThisId !== null){
      return false;
    }
    
    $customerOwnerOfThisCpf = $this->findByCpf($customer->cpf);
    if ($customerOwnerOfThisCpf !== null){
      throw new \DomainException('CPF já cadastrado');
    } 

    $customerOwnerOfThisEmail = $this->findByEmail($customer->email);
    if ($customerOwnerOfThisEmail !== null){
      throw new \DomainException('Email já cadastrado');
    }

    return true;   
  }

  private function ckekIsUpdatableCustomer(Customer $customer)
  {
    $customerOwnerOfThisId = $this->findById($customer->uuid());
    $customerOwnerOfThisEmail = $this->findByEmail($customer->email);

    if ($customerOwnerOfThisId->cpf !== $customer->cpf){
      throw new \DomainException('Nao é permitido alterar o CPF cadastrado');
    }

    if ($customerOwnerOfThisEmail !== null && $customerOwnerOfThisEmail->uuid() !== $customer->uuid()){
      throw new \DomainException('Email já cadastrado');
    }
  }

  public function save(Customer $customer): void
  {
    if ($customer->uuid() === null){
      throw new \DomainException('Nao é possivel salvar um cliente sem informar seu ID');
    }

    if ($this->chekIsNewCustomer($customer)){
      $this->add($customer);
      $this->phoneRepository->save($customer);
      $this->addressRepository->save($customer);
    }

    $this->ckekIsUpdatableCustomer($customer);
    $this->update($customer);
  }

  public function remove(string $id): void
  {
    $customer = $this->findById($id);

    if($customer ===  null){
      throw new \DomainException('Nenhum usuário localizado para o id informado');
    }

    $this->phoneRepository->remove($customer);
    $this->addressRepository->remove($customer);
    $this->delete($customer);
  }
}
