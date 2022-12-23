<?php

namespace MiniErp\Infra\Repository\Common;
use MiniErp\Domain\Common\Addressable;
use MiniErp\Domain\Repository\Common\AddressRepository;
use PDO;
use PDOStatement;

class AddressRepositoryPDO implements AddressRepository
{
  private PDO $pdo;
  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }
  
  public function getAddressFor(Addressable $addressableObj)
  {
    $query = 'SELECT * FROM address
              WHERE ownerId = :uuid';

    $stmt = $this->pdo->prepare($query);
    $stmt->execute(
      [':uuid' => $addressableObj->uuid()]
    );

    return $this->hydrateAddress($stmt, $addressableObj);
  }

  /**
   * @param Addressable $addressableObj
   * @return void
   */
  public function save(Addressable $addressableObj): Void
  {
    if(!$addressableObj->address()){
      $this->deleteAddressInDatabaseFor($addressableObj);
      return;
    }

    $this->deleteAddressInDatabaseFor($addressableObj);
    $this->insertAddressInDatabaseFor($addressableObj);
  }

  private function insertAddressInDatabaseFor(Addressable $addressableObj)
  {
    
    $address = $addressableObj->address();
    $query = 'INSERT INTO address
              (ownerId, street, number, neighborhood, city, state, cep)
              VALUES (:uuid, :street, :number, :neighborhood, :city, :state, :cep)';
    
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([
      ':uuid' => $addressableObj->uuid(),
      ':street' => $address->street(),
      ':number' => $address->number(),
      ':neighborhood' => $address->neighborhood(),
      ':city' => $address->city(),
      ':state' => $address->state(),
      ':cep' => $address->cep()
    ]);
  }

  public function remove(Addressable $addressableObj): void
  {
    $this->deleteAddressInDatabaseFor($addressableObj);
  }

  private function deleteAddressInDatabaseFor(Addressable $addressableObj)
  {
    $query = 'DELETE FROM address
             WHERE  ownerId = :uuid';
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([
      ':uuid' => $addressableObj->uuid()
    ]);
  }

  private function hydrateAddress(PDOStatement $stmt, Addressable $addressableObj): Addressable
  {
    $addressData =  $stmt->fetch(PDO::FETCH_ASSOC);

    if ($addressData == false)
      return $addressableObj;
    
    $addressableObj->addAddress(
      $addressData['street'],
      $addressData['number'],
      $addressData['neighborhood'],
      $addressData['city'],
      $addressData['state'],
      $addressData['cep']
    );
    return $addressableObj;
  }
}