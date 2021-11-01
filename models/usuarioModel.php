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

  protected static function saveRolUsuarioModel($datos)
  {
    $sql = MainModel::connect()->prepare("INSERT INTO USUARIOS_ROLES(usuario_id, rol_id) VALUES (:USUARIO_ID, :ROL_ID);");
    $sql->bindParam(':USUARIO_ID', $datos['USUARIO_ID']);
    $sql->bindParam(':ROL_ID', $datos['ROL_ID']);
    $sql->execute();
    return $sql;
  }
}
