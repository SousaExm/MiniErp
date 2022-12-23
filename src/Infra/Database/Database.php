<?php

namespace MiniErp\Infra\Database;
use PDO;


class Database
{
  private PDO $pdo;
  private array $queryList;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function createCustomerTable()
  {
    $query = 'CREATE TABLE IF NOT EXISTS customer (
              id VARCHAR(13) PRIMARY KEY,
              cpf VARCHAR(14) UNIQUE,
              name VARCHAR(55) NOT NULL, 
              email VARCHAR(255) NOT NULL UNIQUE,
              birthDate DATETIME NOT NULL
            )';

    $this->queryList[] = $query;
  }

  public function createAddressTable()
  {
    $query = 'CREATE TABLE IF NOT EXISTS address (
              ownerId VARCHAR(13) PRIMARY KEY,  
              street VARCHAR(55) NOT NULL,
              number VARCHAR(10) NOT NULL,
              neighborhood VARCHAR(55) NOT NULL,
              city VARCHAR(55) NOT NULL,
              state VARCHAR(20) NOT NULL,
              cep VARCHAR(8) NOT NULL,
              FOREIGN KEY (ownerId) REFERENCES customer (id) 
            )';

    $this->queryList[] = $query;
  }

  public function createPhoneTable()
  {
    $query = 'CREATE TABLE IF NOT EXISTS phone (
              id int PRIMARY KEY AUTO_INCREMENT,
              ownerId VARCHAR(14),  
              areaCode VARCHAR(3) NOT NULL,
              number VARCHAR(15) NOT NULL UNIQUE,
              whatsapp BOOLEAN NOT NULL,
              FOREIGN KEY (ownerId) REFERENCES customer (id) 
            )';

    $this->queryList[] = $query;
  }

  public function createCustomerOrderTable()
  {
    $query = 'CREATE TABLE IF NOT EXISTS customerOrder (
              id VARCHAR(13) PRIMARY KEY,
              customerId VARCHAR(14) NOT NULL,  
              status VARCHAR(5) NOT NULL,
              createdAt VARCHAR(10) NOT NULL,
              amount FLOAT NOT NULL,
              FOREIGN KEY (customerId) REFERENCES customer (id)  
            )';

    $this->queryList[] = $query;
  }

  public function createProductTable()
  {
    $query = 'CREATE TABLE IF NOT EXISTS product (
              id VARCHAR(13) PRIMARY KEY,
              name VARCHAR(55) NOT NULL UNIQUE,  
              description VARCHAR(255) NOT NULL,
              amount FLOAT NOT NULL,
              status VARCHAR(5) NOT NULL,
              unitMeasurement VARCHAR(10) NOT NULL 
            )';

    $this->queryList[] = $query;
  }

  public function createProductPerOrderTable()
  {
    $query = 'CREATE TABLE IF NOT EXISTS productPerOrder (
              id INT PRIMARY KEY AUTO_INCREMENT,
              productId  VARCHAR(13) NOT NULL,  
              orderId VARCHAR(13) NOT NULL,  
              quantity FLOAT NOT NULL,
              FOREIGN KEY (orderId) REFERENCES customerOrder(id), 
              FOREIGN KEY (productId) REFERENCES product(id) 
            )';

    $this->queryList[] = $query;
  }

  // public function createSalesOrderTable()
  // {
  //   $query = 'CREATE TABLE IF NOT EXISTS productPerOrder (
  //             id INT PRIMARY KEY AUTO_INCREMENT,
  //             productId  VARCHAR(13) NOT NULL,  
  //             orderId VARCHAR(13) NOT NULL,  
  //             quantity FLOAT NOT NULL,
  //             FOREIGN KEY (orderId) REFERENCES customerOrder(id), 
  //             FOREIGN KEY (productId) REFERENCES product(id) 
  //           )';

  //   $this->queryList[] = $query;
  // }

  public function createAllTables()
  {
    $this->createCustomerTable();
    $this->createPhoneTable();
    $this->createAddressTable();
    $this->createProductTable();
    $this->createCustomerOrderTable();
    $this->createProductPerOrderTable();
    // $this->createSalesOrderTable();
  }

  public function execute()
  {
    foreach($this->queryList as $query)
    {
      $this->pdo->exec($query);
    }
  }

  public function clearQuerys()
  {
    $this->queryList = [];
  }

  public function addQuery(string $query)
  {
    $this->queryList[] = $query;
  }

  public function dropAllTables()
  {
    $query = 'DROP TABLE phone, address, productPerOrder, order, product, customer';
    $this->queryList[] = $query;
  }
}
