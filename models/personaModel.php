<?php
require_once 'mainModel.php';
class PersonaModel extends MainModel
{
  protected static function savePersonaModel($datos)
  {
    $sql = MainModel::connect()->prepare("INSERT INTO PERSONAS(dni_ruc, nombre, apellido, correo, cod_estudiante, puesto_id) VALUES (:DNI_RUC, :NOMBRE, :APELLIDO, :CORREO, :COD_ESTUDIANTE, :PUESTO_ID);");

    $sql->bindParam(':DNI_RUC', $datos['DNI_RUC']);
    $sql->bindParam(':NOMBRE', $datos['NOMBRE']);
    $sql->bindParam(':APELLIDO', $datos['APELLIDO']);
    $sql->bindParam(':CORREO', $datos['CORREO']);
    $sql->bindParam(':COD_ESTUDIANTE', $datos['COD_ESTUDIANTE']);
    $sql->bindParam(':PUESTO_ID', $datos['PUESTO_ID']);

    $sql->execute();
    return $sql;
  }

  protected static function deletePersonaModel($id)
  {
    $sql = MainModel::connect()->prepare("DELETE FROM PERSONAS WHERE persona_id=$id");
    $sql->execute();
    return $sql;
  }

  protected static function datosPersonaModel($tipo, $id)
  {
    if ($tipo == 'Unico') {
      $sql = MainModel::connect()->prepare("SELECT * FROM PERSONAS WHERE persona_id=:ID");
      $sql->bindParam(':ID', $id);
    } elseif ($tipo == 'Conteo') {
      $sql = MainModel::connect()->prepare("SELECT persona_id FROM PERSONAS");
    }
    $sql->execute();
    return $sql;
  }

  protected static function updatePersonaModel($datos)
  {
    $sql = MainModel::connect()->prepare("UPDATE PERSONAS SET dni_ruc=:DNI_RUC, nombre=:NOMBRE, apellido=:APELLIDO, correo=:CORREO, cod_estudiante=:COD_ESTUDIANTE, puesto_id=:PUESTO_ID WHERE persona_id=:ID");

    $sql->bindParam(':DNI_RUC', $datos['DNI_RUC']);
    $sql->bindParam(':NOMBRE', $datos['NOMBRE']);
    $sql->bindParam(':APELLIDO', $datos['APELLIDO']);
    $sql->bindParam(':CORREO', $datos['CORREO']);
    $sql->bindParam(':COD_ESTUDIANTE', $datos['COD_ESTUDIANTE']);
    $sql->bindParam(':PUESTO_ID', $datos['PUESTO_ID']);
    $sql->bindParam(':ID', $datos['ID']);

    $sql->execute();
    return $sql;
  }
}
