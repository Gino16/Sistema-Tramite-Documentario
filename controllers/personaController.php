<?php
require_once './models/personaModel.php';
class PersonaController extends PersonaModel
{

  public function savePersona()
  {
    if (isset($_POST['dni_ruc']) && isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['id_puesto'])) {
      $dni_ruc = $_POST['dni_ruc'];
      $nombre = $_POST['nombre'];
      $apellido = (isset($_POST['apellido']) ? $_POST['apellido'] : '');
      $correo = $_POST['correo'];
      $cod_estudiante = (isset($_POST['cod_estudiante']) ? $_POST['cod_estudiante'] : '');
      $id_puesto = $_POST['id_puesto'];

      $datos = [
        "DNI_RUC" => $dni_ruc,
        "NOMBRE" => $nombre,
        "APELLIDO" => $apellido,
        "CORREO" => $correo,
        "COD_ESTUDIANTE" => $cod_estudiante,
        "ID_PUESTO" => $id_puesto
      ];

      $result = PersonaModel::savePersonaModel($datos);

      if ($result->rowCount() == 1) {
        header('Location:' . SERVERURL . 'persona-list/');
      } else {
        echo var_dump($result->errorInfo());
      }
    }
  }

  public function listPersonas()
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

  public function listPuestos()
  {
    $conn = MainModel::connect();
    $sql = $conn->query("SELECT * FROM puestos");
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
