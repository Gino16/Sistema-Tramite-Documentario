<?php
require_once 'mainModel.php';
class UsuarioModel extends MainModel
{
  protected static function saveUsuarioModel($datos)
  {
    $sql = MainModel::connect()->prepare("INSERT INTO USUARIOS(username, password, enabled, dni_ruc) VALUES (:USERNAME, :PASSWORD, :ENABLED, :DNI_RUC);");
    $sql->bindParam(":USERNAME", $datos['USERNAME']);
    $sql->bindParam(":PASSWORD", $datos['PASSWORD']);
    $sql->bindParam(":ENABLED", $datos['ENABLED']);
    $sql->bindParam(":DNI_RUC", $datos['DNI_RUC']);

    $sql->execute();

    return $sql;
  }
}
