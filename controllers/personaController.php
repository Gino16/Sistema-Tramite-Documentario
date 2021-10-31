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
    $puesto = MainModel::cleanString($_POST['puesto_id']);

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
      "PUESTO_ID" => $puesto
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

  public function paginarPersonasController($page, $numReg, $role, $url, $search)
  {
    $page = MainModel::cleanString($page);
    $numReg = MainModel::cleanString($numReg);
    $role = MainModel::cleanString($role);
    $url = MainModel::cleanString($url);
    $url = SERVERURL . $url . '/';
    $search = MainModel::cleanString($search);

    $table = "";

    $page = (isset($page) && $page > 0) ? (int)$page : 1;
    $start = ($page > 0) ? (($page * $numReg) - $numReg) : 0;

    if (isset($search) && $search != "") {
      $query = "SELECT SQL_CALC_FOUND_ROWS pe.*, pu.nombre as nombre_puesto FROM PERSONAS pe INNER JOIN PUESTOS pu ON pe.puesto_id = pu.puesto_id  WHERE pe.nombre LIKE '%$search%' OR pe.apellido LIKE '%$search%' OR pe.correo LIKE '%$search%' OR pe.cod_estudiante LIKE '%$search%' ORDER BY pe.apellido ASC LIMIT $start, $numReg";
    } else {
      $query = "SELECT SQL_CALC_FOUND_ROWS pe.*, pu.nombre as nombre_puesto FROM PERSONAS pe INNER JOIN PUESTOS pu ON pe.puesto_id = pu.puesto_id ORDER BY apellido ASC LIMIT $start, $numReg";
    }

    $conn = MainModel::connect();

    $data = $conn->query($query);
    $data = $data->fetchAll();

    $total = $conn->query("SELECT FOUND_ROWS()");
    $total = (int)$total->fetchColumn();

    $nPages = ceil($total / $numReg);

    if ($total >= 1 && $page <= $nPages) {
      $count = $start + 1;
      $regStart = $start + 1;
      foreach ($data as $row) {

        $table .= '
            <tr class="text-center">
              <td> ' . $count++ . '</td>
              <td>' . $row['dni_ruc'] . ' </td >
              <td > ' . $row['apellido'] . ' ' . $row['nombre'] . ' </td >
              <td > ' . $row['correo'] . ' </td >
              <td > ' . ((isset($row['cod_estudiante']) && $row['cod_estudiante'] != "") ? $row['cod_estudiante'] : " - ") . ' </td >
              <td > ' . $row['nombre_puesto'] . ' </td >
              <td> 
                <a href="' . SERVERURL . 'persona-update/' . MainModel::encryption($row['persona_id']) . '" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></a>
                <form action="' . SERVERURL . 'ajax/personaAjax.php" method="post" class="d-inline FormularioAjax" data-form="delete" autocomplete="off">
                  <input type="hidden" name="persona_id_del" value="' . MainModel::encryption($row['persona_id']) . '">
                    <button type="submit" class="btn btn-danger btn-sm">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
              </td>
            </tr >
				';
      }
      $regEnd = $count - 1;
    } else {
      if ($total >= 1) {
        $table .= '
        <tr class="text-center" >
            <td i = "9" >
                <a class="btn btn-primary btn-sm" href = "' . $url . '" > Haga click aquí para recargar la lista </a >
            </td >
        </tr >
				';
      } else {
        $table .= '
        <tr class="text-center" >
            <td colspan = "9" > No existen datos en el sistema </td >
        </tr >
				';
      }
    }

    $table .= '</tbody ></table ></div > ';

    if ($total >= 1 && $page <= $nPages) {
      $table .= '<p class="text-end" > Mostrando usuarios ' . $regStart . ' al ' . $regEnd . ' de un total de ' . $total . ' </p > ';
      $table .= MainModel::tablePages($page, $nPages, $url, 10);
    }
    return $table;
  }

  public function deletePersonaController()
  {
    $id = MainModel::decryption($_POST['persona_id_del']);
    $id = MainModel::cleanString($id);

    //Check Persona in DB
    $checkPersona = MainModel::executeSimpleQuery("SELECT persona_id FROM PERSONAS WHERE persona_id = '$id'");
    if ($checkPersona->rowCount() <= 0) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "No se pudo eliminar a la persona",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }
    $eliminarPersona = PersonaModel::deletePersonaModel($id);

    if ($eliminarPersona->rowCount() == 1) {
      $alerta = [
        "Alert" => "reload",
        "title" => "Persona Eliminada",
        "text" => "La persona ha sido eliminado exitosamente",
        "icon" => "success"
      ];
    } else {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "No se pudo eliminar a la persona",
        "icon" => "error"
      ];
    }
    echo json_encode($alerta);
  }

  public function datosPersonaController($tipo, $id)
  {
    $tipo = MainModel::cleanString($tipo);
    $id = MainModel::decryption($id);
    $id = MainModel::cleanString($id);

    return PersonaModel::datosPersonaModel($tipo, $id);
  }

  public function updatePersonaController()
  {
    $id = MainModel::decryption($_POST['persona_id_up']);
    $id = MainModel::cleanString($id);

    $checkPersona = MainModel::executeSimpleQuery("SELECT * FROM PERSONAS WHERE persona_id = '$id'");
    if ($checkPersona->rowCount() <= 0) {
      $alerta = [
        "Alerta" => "simple",
        "Titulo" => "Ocurrió un error inesperado",
        "Texto" => "La persona ha editar no existe en el sistema",
        "Tipo" => "error"
      ];
      echo json_encode($alerta);
      exit();
    } else {
      $campos = $checkPersona->fetch();
    }
    $dniRuc = MainModel::cleanString($_POST['dni_ruc_up']);
    $nombre = MainModel::cleanString($_POST['nombre_up']);
    $apellido = MainModel::cleanString($_POST['apellido_up']);
    $correo = MainModel::cleanString($_POST['correo_up']);
    $codEstudiante = MainModel::cleanString($_POST['cod_estudiante_up']);
    $puesto = MainModel::cleanString($_POST['puesto_id_up']);

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
    if ($dniRuc != $campos['dni_ruc']) {
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
    }

    // Comprobar que correo es unico
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
      if ($correo != $campos['correo']) {
        $checkCorreo = MainModel::executeSimpleQuery("SELECT correo from PERSONAS where correo = '$correo'");
        if ($checkCorreo->rowCount() > 0) {
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
          "text" => "El CORREO no tiene un formato no permitido",
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
        "PUESTO_ID" => $puesto,
        "ID" => $id
      ];

      $updatePersona = PersonaModel::updatePersonaModel($datosPersona);

      if ($updatePersona->rowCount() == 1) {
        $alerta = [
          "Alert" => "reload",
          "title" => "Persona Actualizada",
          "text" => "La persona ha sido actualizada exitosamente",
          "icon" => "success"
        ];
      } else {
        $alerta = [
          "Alert" => "simple",
          "title" => "Ocurrió un error inesperado",
          "text" => "No se pudo actualizar a la persona",
          "icon" => "error"
        ];
      }
      echo json_encode($alerta);
    }
  }

  public function listarPuestos()
  {
    $sql = MainModel::connect()->query("SELECT * FROM PUESTOS");
    $datos = $sql->fetchAll();
    $select = '';
    foreach ($datos as $row) {
      $select .= '
				<option value = "' . $row['puesto_id'] . '">' . $row['nombre'] . ' </option >
				';
    }
    return $select;
  }
}
