<?php
require_once './config/SERVER.php';

class MainModel
{
  protected static function connect()
  {
    $conn = new PDO(SGBD, USER, PASSWORD);
    $conn->exec('SET CHARACTER SET utf8');
    return $conn;
  }

  protected static function executeSimpleQuery($query)
  {
    $sql = self::connect()->prepare($query);
    $sql->execute();
    return $sql;
  }
}
