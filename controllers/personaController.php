<?php

if ($peticionAjax) {
  require_once '../models/personaModel.php';
} else {
  require_once './models/personaModel.php';
}
class PersonaController extends PersonaModel
{

  public function registrarPersona()
  {
  }

  public function listarPersonas()
  {
    $conn = MainModel::connect();
    $datos = $conn->query("SELECT personas.*, puestos.nombre as 'puesto_nombre' FROM personas INNER JOIN puestos ON personas.id_puesto = puestos.id_puesto ORDER BY personas.apellido;");
    $datos = $datos->fetchAll();
    $tabla = '';
    $count = 1;
    foreach ($datos as $rows) {
      $tabla .= '
      <tr>
        <td>' . $count++ . '</td>
        <td>' . $rows['dni_ruc'] . '</td>
        <td>' . $rows['apellido'] . '</td>
        <td>' . $rows['nombre'] . '</td>
        <td>' . $rows['correo'] . '</td>
        <td>' . ((!isset($rows['cod_estudiante'])  || $rows['cod_estudiante'] == "") ? '-' : $rows['cod_estudiante']) . '</td>
        <td>' . $rows['puesto_nombre'] . '</td>
        <td>
        <a class="btn btn-success btn-sm" href="#"><i class="fas fa-pen"></i></a>
        <a class="btn btn-danger btn-sm" href="#"><i class="fas fa-trash"></i></a>
        </td>
      </tr>
      ';
    }
    return $tabla;
  }

  public function listarPuestos()
  {
    $sql = MainModel::connect()->query("SELECT * FROM PUESTOS");
    $datos = $sql->fetchAll();
    $select = '';
    foreach ($datos as $row) {
      $select .= '
      <option value="' . $row['id_puesto'] . '">' . $row['nombre'] . '</option>
      ';
    }
    return $select;
  }
}
