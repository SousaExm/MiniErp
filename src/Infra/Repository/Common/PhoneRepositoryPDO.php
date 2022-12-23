<?php

namespace MiniErp\Infra\Repository\Common;
use MiniErp\Domain\Common\Phone;
use MiniErp\Domain\Common\PhoneCallable;
use MiniErp\Domain\Repository\Common\PhoneRepository;
use PDO;
use PDOStatement;

class PhoneRepositoryPDO implements PhoneRepository
{

  private PDO $pdo;

  public function __construct(PDO $pdo) 
  {
    $this->pdo = $pdo;
  }

	public function getPhonesFor(PhoneCallable $phoneCallableObj) 
  {
    {
      $query = 'SELECT * FROM phone
                WHERE ownerId = :uuid';
  
      $stmt = $this->pdo->prepare($query);
      $stmt->execute(
        [':uuid' => $phoneCallableObj->uuid()]
      );
      return $this->hydratePhones($stmt, $phoneCallableObj);
    }
	}
	
	public function save(PhoneCallable $phoneCallableObj): void 
  {
    $this->deletePhonesInDatabaseFor($phoneCallableObj);
    foreach($phoneCallableObj->phones() as $phone){
      $this->insertPhone($phoneCallableObj, $phone);
    }
	}
	
	public function remove(PhoneCallable $phoneCallableObj): void 
  {
    $this->deletePhonesInDatabaseFor($phoneCallableObj);
	}

  private function hydratePhones(PDOStatement $stmt, PhoneCallable $phoneCallableObj): PhoneCallable
  {
    $phoneDataList =  $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(!isset($phoneDataList)){
      return $phoneCallableObj;
    }
    
    foreach($phoneDataList as $phone) 
    {
      $phoneCallableObj->addPhone(
        $phone['areaCode'],
        $phone['number'],
        $phone['whatsapp']
      );
    }
    return $phoneCallableObj;
  }

  private function insertPhone(PhoneCallable $phoneCallableObjt, Phone $phone): void
  {
    $query = 'INSERT INTO phone
              (ownerId, areaCode, number, whatsapp)
              VALUES (:uuid, :areaCode, :number, :whatsapp)';
    
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':uuid', $phoneCallableObjt->uuid());
    $stmt->bindValue(':areaCode', $phone->areaCode());
    $stmt->bindValue(':number', $phone->number());
    $stmt->bindValue(':whatsapp', $phone->hasWhatsApp(), PDO::PARAM_BOOL);
    $stmt->execute();
  }
  
  private function deletePhonesInDatabaseFor(PhoneCallable $phoneCallableObj)
  {
    $query = 'DELETE FROM phone
             WHERE  ownerId = :uuid';
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([
      ':uuid' => $phoneCallableObj->uuid()
    ]);
  }
}