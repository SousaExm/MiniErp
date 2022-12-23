<?php

namespace MiniErp\Domain\Repository\Customer;
use MiniErp\Domain\Customer\{
  Customer,
  Cpf
};

interface CustomerRepository
{
  /**
   * @return Customer[]
   */
  public function allCustomers(): array;
  public function getCustomerByCpf(string $cpf): Customer | false;
  public function getCustomerById(string $id): Customer | false;
  public function remove(Customer $customer): void;
  public function save(Customer $customer): Customer;
}
