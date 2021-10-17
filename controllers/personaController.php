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
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM PERSONAS WHERE nombre LIKE '%$search%' OR apellido LIKE '%$search%' OR correo LIKE '%$search%' OR cod_estudiante LIKE '%$search%' ORDER BY apellido ASC LIMIT $start, $numReg";
		} else {
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM PERSONAS ORDER BY apellido ASC LIMIT $start, $numReg";
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
              <td > ' . ((isset($row['cod_estudiante']) && $row['cod_estudiante'] != "") ? $row['cod_estudiante'] : " - " ).' </td >
            </tr >
				';
      }
      $regEnd = $count - 1;
    } else {
      if ($total >= 1) {
        $table .= '
        <tr class="text-center" >
            <td colspan = "9" >
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

  public function listarPuestos()
  {
    $sql = MainModel::connect()->query("SELECT * FROM PUESTOS");
    $datos = $sql->fetchAll();
    $select = '';
    foreach ($datos as $row) {
      $select .= '
				<option value = "' . $row['id_puesto'] . '">' . $row['nombre'] . ' </option >
				';
    }
    return $select;
  }
}
