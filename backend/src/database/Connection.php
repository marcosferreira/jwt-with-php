<?php

namespace Backend\database;

use PDO;

class Connection
{
  public static function connect()
  {
    return new PDO("mysql:host=localhost;dbname=jwtphp", "root", "", [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ]);
  }
}
