<?php
require_once 'mainModel.php';

class LoginModel extends MainModel
{
  protected static function loginModel($datos)
  {
    $sql = MainModel::connect()->prepare("SELECT * FROM USUARIOS WHERE username = :USERNAME AND password = :PASSWORD");
    $sql->bindParam(':USERNAME', $datos['USERNAME']);
    $sql->bindParam(':PASSWORD', $datos['PASSWORD']);
    $sql->execute();
    return $sql;
  }
}
