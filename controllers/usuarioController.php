<?php


if ($peticionAjax) {
  require_once '../models/usuarioModel.php';
} else {
  require_once './models/usuarioModel.php';
}


class UsuarioController extends UsuarioModel
{
  public function saveUsuarioController()
  {
    $username = MainModel::cleanString($_POST['username']);
    $password = MainModel::cleanString($_POST['password']);
    $password2 = MainModel::cleanString($_POST['password2']);
    $dniRuc = MainModel::cleanString($_POST['dni_ruc']);
    $enabled = (isset($_POST['enabled'])) ? 1 : 0;
    $rol = MainModel::cleanString($_POST['rol']);

    if ($username == "" || $password == "" || $password2 == "" || $dniRuc == "") {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "No ha llenado los campos necesarios para registrar al usuario",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }

    // Verificar datos cumplen con formato
    if (!MainModel::checkData("[a-zA-Z0-9]{1,35}", $username)) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "El NOMBRE DE USUARIO no cumple con el formato solicitado",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }

    if (!MainModel::checkData("[a-zA-Z0-9$@.-]{7,100}", $password) || !MainModel::checkData("[a-zA-Z0-9$@.-]{7,100}", $password2)) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "Las CONTRASEÑAS no cumple con el formato solicitado",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }

    if (!MainModel::checkData("[0-9]{1,17}", $dniRuc)) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "El DNI no cumple con el formato solicitado",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }

    if ($rol < 1) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "El ROL enviado no es correcto",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    }
    if ($password != $password2) {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "Las CONTRASEÑAS no coinciden",
        "icon" => "error"
      ];
      echo json_encode($alerta);
      exit();
    } else {
      $password = MainModel::encryption($password2);
    }

    $datosUsuario = [
      'USERNAME' => $username,
      'PASSWORD' => $password,
      'ENABLED' => $enabled,
      'DNI_RUC' => $dniRuc
    ];

    $usuarioAgregado = UsuarioModel::saveUsuarioModel($datosUsuario);

    if ($usuarioAgregado->rowCount() == 1) {
      $query = MainModel::executeSimpleQuery("SELECT * FROM USUARIOS WHERE dni_ruc = '$dniRuc'");
      $usuario = $query->fetchAll();
      $datosRolUsuario = [
        'USUARIO_ID' => $usuario[0]['usuario_id'],
        'ROL_ID' => $rol
      ];

      $rolUsuarioAgregado = UsuarioModel::saveRolUsuarioModel($datosRolUsuario);
      if ($rolUsuarioAgregado->rowCount() == 1) {
        $alerta = [
          "Alert" => "clean",
          "title" => "Usuario registrado",
          "text" => "El USUARIO fue registrado exitosamente en el sistema",
          "icon" => "success"
        ];
      } else {
        $alerta = [
          "Alert" => "simple",
          "title" => "Ocurrió un error inesperado",
          "text" => "No se pudo registrar el usuario en el sistema",
          "icon" => "error"
        ];
      }
    } else {
      $alerta = [
        "Alert" => "simple",
        "title" => "Ocurrió un error inesperado",
        "text" => "No se pudo registrar el usuario en el sistema",
        "icon" => "error"
      ];
    }
    echo json_encode($alerta);
  }

  public function paginadorUsuarioController($page, $numReg, $role, $url, $search)
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
      $query = "SELECT SQL_CALC_FOUND_ROWS u.usuario_id, u.username, u.password, p.apellido, p.nombre, r.nombre FROM usuarios u INNER JOIN personas p ON u.dni_ruc = p.dni_ruc INNER JOIN usuarios_roles ur ON ur.usuario_id = u.usuario_id INNER JOIN roles r ON ur.rol_id = r.rol_id WHERE u.username LIKE '%$search%' OR p.apellido LIKE '%$search%' OR p.nombre LIKE '%$search%' ORDER BY u.username ASC LIMIT $start, $numReg ";
    } else {
      $query = "SELECT SQL_CALC_FOUND_ROWS pe.*, pu.nombre as nombre_puesto FROM PERSONAS pe INNER JOIN PUESTOS pu ON pe.puesto_id = pu.puesto_id ORDER BY apellido ASC LIMIT $start, $numReg";
    }
  }

  public function listarUsuarios()
  {
    $sql = MainModel::executeSimpleQuery("SELECT * FROM USUARIOS");
    $datos = $sql->fetchAll();
    return $datos;
  }

  public function listarRoles()
  {
    $sql = MainModel::executeSimpleQuery("SELECT * FROM ROLES");
    $datos = $sql->fetchAll();
    return $datos;
  }
}
