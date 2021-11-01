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
