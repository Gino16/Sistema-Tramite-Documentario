<?php
require_once 'mainModel.php';
class PersonaModel extends MainModel
{
  protected static function savePersonaModel($datos)
  {
    $sql = MainModel::connect()->prepare("INSERT INTO personas(dni_ruc, nombre, apellido, correo, cod_estudiante, id_puesto) VALUES (:DNI_RUC, :NOMBRE, :APELLIDO, :CORREO, :COD_ESTUDIANTE, :ID_PUESTO);");

    $sql->bindParam(':DNI_RUC', $datos['DNI_RUC']);
    $sql->bindParam(':NOMBRE', $datos['NOMBRE']);
    $sql->bindParam(':APELLIDO', $datos['APELLIDO']);
    $sql->bindParam(':CORREO', $datos['CORREO']);
    $sql->bindParam(':COD_ESTUDIANTE', $datos['COD_ESTUDIANTE']);
    $sql->bindParam(':ID_PUESTO', $datos['ID_PUESTO']);

    $sql->execute();
    return $sql;
  }
}
