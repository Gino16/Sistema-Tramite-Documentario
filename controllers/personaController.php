<?php
require_once './models/personaModel.php';
class PersonaController extends PersonaModel
{
  public function listPersonas()
  {
    $conn = MainModel::connect();
    $datos = $conn->query("SELECT personas.*, puestos.nombre as 'puesto_nombre' FROM personas INNER JOIN puestos ON personas.id_puesto = puestos.id_puesto ORDER BY personas.apellido;");
    $datos = $datos->fetchAll();
    $tabla = '';

    foreach ($datos as $rows) {
      $tabla .= '
      <tr>
        <td>' . $rows['id_persona'] . '</td>
        <td>' . $rows['dni_ruc'] . '</td>
        <td>' . $rows['apellido'] . '</td>
        <td>' . $rows['nombre'] . '</td>
        <td>' . $rows['correo'] . '</td>
        <td>' . $rows['cod_estudiante'] . '</td>
        <td>' . $rows['puesto_nombre'] . '</td>
      </tr>
      ';
    }
    //echo var_dump($datos);
    return $tabla;
  }
}
