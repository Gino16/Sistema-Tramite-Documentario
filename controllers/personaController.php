<?php

if ($peticionAjax) {
  require_once '../models/personaModel.php';
} else {
  require_once './models/personaModel.php';
}
class PersonaController extends PersonaModel
{

  public function savePersonaController()
  {
    $dni_ruc = MainModel::cleanString($_POST['dni_ruc_save']);
    $nombre = MainModel::cleanString($_POST['nombre']);
    $apellido = MainModel::cleanString($_POST['apellido']);
    $correo = MainModel::cleanString($_POST['correo']);
    $codEstudiante = MainModel::cleanString($_POST['cod_estudiante']);
    $puesto = MainModel::cleanString($_POST['id_puesto']);

    // Verificar campos vacios
    if ($dni_ruc == "" || $nombre == "" || $apellido == "" || $correo == "") {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "No ha llenado los campos necesarios para registrar a la persona",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }

    // Verificar datos cumplen con formato
    if (!MainModel::checkData("[0-9]{1,27}", $dni_ruc)) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "El DNI o RUC no cumple con el formato solicitado",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }

    if (!MainModel::checkData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $nombre)) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "El NOMBRE no cumple con el formato solicitado",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }

    if (!MainModel::checkData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $apellido)) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "El APELLIDO no cumple con el formato solicitado",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }

    if (!empty($codEstudiante) && !MainModel::checkData("[0-9]{10}", $codEstudiante)) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "El CODIGO DE ESTUDIANTE no cumple con el formato solicitado",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }
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
