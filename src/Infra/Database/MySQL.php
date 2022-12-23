<?php

namespace MiniErp\Infra\Database;
use PDO;

class MySQL
{
  public static function connect()
  {
    return new PDO('mysql:host=localhost;dbname=mini_erp', 'root', '110116');
  }
}
