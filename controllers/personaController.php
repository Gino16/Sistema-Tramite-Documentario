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
    $dniRuc = MainModel::cleanString($_POST['dni_ruc_save']);
    $nombre = MainModel::cleanString($_POST['nombre']);
    $apellido = MainModel::cleanString($_POST['apellido']);
    $correo = MainModel::cleanString($_POST['correo']);
    $codEstudiante = MainModel::cleanString($_POST['cod_estudiante']);
    $puesto = MainModel::cleanString($_POST['id_puesto']);

    // Verificar campos vacios
    if ($dniRuc == "" || $nombre == "" || $apellido == "" || $correo == "") {
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
    if (!MainModel::checkData("[0-9]{1,27}", $dniRuc)) {
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

    // Comprobar que dni es único
    $checkDni = MainModel::executeSimpleQuery("SELECT dni_ruc from PERSONAS where dni_ruc = '$dniRuc'");
    if ($checkDni->rowCount() > 0) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "El DNI digitado ya se encuentra registrado en la base de datos",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }

    // Comprobar que correo es unico
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
      $checkEmail = MainModel::executeSimpleQuery("SELECT correo from PERSONAS WHERE correo = '$correo'");
      if ($checkEmail->rowCount() > 0) {
        $alerta = [
          "Alert" => "simple",
          "title" => "Ocurrió un error inesperado",
          "text" => "El CORREO digitado ya se encuentra registrado en la base de datos",
          "icon" => "error"
        ];
        echo json_encode($alerta);
        exit();
      }
    } else {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "El CORREO tiene un formato no permitido",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }

    // Comprobar que se envio puesto correcto
    if ($puesto < 0) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "El PUESTO seleccionado no es válido",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }

    $datosPersona = [
      "DNI_RUC" => $dniRuc,
      "NOMBRE" => $nombre,
      "APELLIDO" => $apellido,
      "CORREO" => $correo,
      "COD_ESTUDIANTE" => $codEstudiante,
      "ID_PUESTO" => $puesto
    ];

    $persona = PersonaModel::savePersonaModel($datosPersona);

    if ($persona->rowCount() == 1) {
      $alerta = [
        "Alert" => "clean",
        "title" => "Persona registrada",
        "text" => "La PERSONA fue registrada en el sistema exitosamente",
        "icon" => "success"
      ];
    } else {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "No se pudo registrar a la persona",
        "icon" => "error"
      ];
    }
    echo json_encode($alerta);
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
