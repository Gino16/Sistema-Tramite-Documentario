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
      $query = "SELECT SQL_CALC_FOUND_ROWS u.usuario_id, u.username, u.password, CONCAT(p.apellido,', ',p.nombre) as nombre_usuario, r.nombre as rol FROM USUARIOS u INNER JOIN PERSONAS p ON u.dni_ruc = p.dni_ruc INNER JOIN USUARIOS_ROLES ur ON ur.usuario_id = u.usuario_id INNER JOIN ROLES r ON ur.rol_id = r.rol_id WHERE u.username LIKE '%$search%' OR p.apellido LIKE '%$search%' OR p.nombre LIKE '%$search%' ORDER BY u.username ASC LIMIT $start, $numReg ";
    } else {
      $query = "SELECT SQL_CALC_FOUND_ROWS u.usuario_id, u.username, u.password, CONCAT(p.apellido,', ',p.nombre) as nombre_usuario, r.nombre as rol FROM USUARIOS u INNER JOIN PERSONAS p ON u.dni_ruc = p.dni_ruc INNER JOIN USUARIOS_ROLES ur ON ur.usuario_id = u.usuario_id INNER JOIN ROLES r ON ur.rol_id = r.rol_id ORDER BY u.username ASC LIMIT $start, $numReg";
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
              <td>' . $row['username'] . ' </td >
              <td > ' . $row['password'] . ' ' . $row['nombre'] . ' </td >
              <td > ' . $row['nombre_usuario'] . ' </td >
              <td > ' . $row['rol'] . ' </td >
              <td> 
                <a href="' . SERVERURL . 'usuario-update/' . MainModel::encryption($row['usuario_id']) . '" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></a>
                <form action="' . SERVERURL . 'ajax/usuarioAjax.php" method="post" class="d-inline FormularioAjax" data-form="delete" autocomplete="off">
                  <input type="hidden" name="usuario_id_del" value="' . MainModel::encryption($row['usuario_id']) . '">
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
