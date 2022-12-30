<?php

use MiniErp\Domain\Common\Phone;
use MiniErp\Domain\Customer\CustomerPhonesList;
use PHPUnit\Framework\TestCase;

class CustomerPhonesListTest extends TestCase
{ 
  /**
   * @dataProvider phonesProvider 
   */
  public function testAddANewPhone(array $data)
  {
    [$phonesList, $phone1] = $data;
    
    $phonesList->addPhone(...$phone1);
    $this->assertContainsEquals(new Phone(...$phone1), $phonesList->phones());
    $this->assertContainsOnly(Phone::class, $phonesList->phones());
  }

  /**
   * @dataProvider phonesProvider 
   */
  public function testTryAddTwoSamePhonesNumber($data)
  {
    [$phonesList, $phone1] = $data;

    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Nao é possível adicionar dois telefones com o mesmo número');
    
    $phonesList->addPhone(...$phone1);
    $phonesList->addPhone(...$phone1);
  }

  /**
   * @dataProvider phonesProvider 
   */
  public function testTryAddThreePhonesForCustomer($data)
  {
    [$phonesList, $phone1, $phone2, $phone3] = $data;

    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Nao é possível possuir mais de dois telefones por cliente');

    $phonesList->addPhone(...$phone1);
    $phonesList->addPhone(...$phone2);
    $phonesList->addPhone(...$phone3);
  }

  /**
   * @dataProvider phonesProvider 
   */
  public function testTryRemoveOneInexistentPhone($data)
  {
    [$phonesList, $phone1] = $data;

    $this->expectException(\DomainException::class);
    $this->expectExceptionMessage('Nao foi encontrado telefone correspondente ao número informado');

    $phonesList->removePhone($phone1['phoneNumber']);
  }

  /**
   * @dataProvider phonesProvider 
   */
  public function testRemovePhone($data)
  {
    [$phonesList, $phone1] = $data;

    $phonesList->addPhone(...$phone1);
    $phonesList->removePhone($phone1['phoneNumber']);
    
    $this->assertNotContains(new Phone(...$phone1), $phonesList->phones());
    $this->assertCount(0, $phonesList->phones());
    $this->assertContainsEquals(new Phone(...$phone1), $phonesList->lastRemovedPhones());
    $this->assertCount(1, $phonesList->lastRemovedPhones());
  }

  /**
   * @dataProvider phonesProvider 
   */
  public function testRemoveMultiplePhones($data)
  {
    [$phonesList, $phone1, $phone2, $phone3, $phone4] = $data;

    $phonesList->addPhone(...$phone1);
    $phonesList->addPhone(...$phone2);

    $phonesList->removePhone($phone1['phoneNumber']);
    $phonesList->removePhone($phone2['phoneNumber']);

    $phonesList->addPhone(...$phone3);
    $phonesList->addPhone(...$phone4);

    $phonesList->removePhone($phone3['phoneNumber']);
    $phonesList->removePhone($phone4['phoneNumber']);
    
    $this->assertCount(0, $phonesList->phones());
    $this->assertCount(4, $phonesList->lastRemovedPhones());

    $this->assertContainsEquals(new Phone(...$phone1), $phonesList->lastRemovedPhones());
    $this->assertContainsEquals(new Phone(...$phone2), $phonesList->lastRemovedPhones());
    $this->assertContainsEquals(new Phone(...$phone3), $phonesList->lastRemovedPhones());
    $this->assertContainsEquals(new Phone(...$phone4), $phonesList->lastRemovedPhones());
  }

  /**
   * @dataProvider phonesProvider 
   */
  public function testUpdatePhones($data)
  {
    [$phonesList, $phone1, $phone2] = $data;

    $phonesList->addPhone(...$phone1);
    $phonesList->updatePhone($phone1['phoneNumber'], ...$phone2);

    $this->assertContainsEquals(new Phone(...$phone2), $phonesList->phones());
    $this->assertContainsEquals(new Phone(...$phone1), $phonesList->lastRemovedPhones());

    $this->assertNotContains(new Phone(...$phone1), $phonesList->phones());
    $this->assertNotContains(new Phone(...$phone2), $phonesList->lastRemovedPhones());
  }

  public function phonesProvider()
  {
    $phonesList = new CustomerPhonesList();
    $phone1 = array('areaCode' => '011', 'phoneNumber' => '999998989', 'hasWhatsApp' => true);
    $phone2 = array('areaCode' => '011', 'phoneNumber' => '999998981', 'hasWhatsApp' => true);
    $phone3 = array('areaCode' => '011', 'phoneNumber' => '999998985', 'hasWhatsApp' => true);
    $phone4 = array('areaCode' => '011', 'phoneNumber' => '999998981', 'hasWhatsApp' => true);

    $data = [$phonesList, $phone1, $phone2, $phone3, $phone4];

    return [
      "Phones provider" => [$data]
    ];
  }
}
