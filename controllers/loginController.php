<?php
if ($peticionAjax) {
  require_once '../models/loginModel.php';
} else {
  require_once './models/loginModel.php';
}
class LoginController extends LoginModel
{
  public function loginController()
  {
    $username = mainModel::cleanString($_POST['username']);
    $password = mainModel::cleanString($_POST['password']);

    if ($username == "" || $password == "") {
      echo '
      <script>
      Swal.fire({
        title: "Ocurrio un error inesperado",
        text: "No ha llenado los campos para iniciar sesion",
        icon: "error",
        confirmButtonText: "Aceptar"
      });
      </script>
      ';
      exit();
    }

    if (!MainModel::checkData("[a-zA-Z0-9]{1,35}", $username)) {
      echo '
      <script>
      Swal.fire({
        title: "Ocurrió un error inesperado",
        text: "El NOMBRE DE USUARIO no coincide con el formato solicitado",
        icon: "error",
        confirmButtonText: "Aceptar"
      });
      </script>
      ';
      exit();
    }
    if (!MainModel::checkData("[a-zA-Z0-9$@.-]{7,100}", $password)) {
      echo '
      <script>
      Swal.fire({
        title: "Ocurrió un error inesperado",
        text: "La CONTRASEÑA no coincide con el formato solicitado",
        icon: "error",
        confirmButtonText: "Aceptar"
      });
      ';
      exit();
    }

    $password = mainModel::encryption($password);
    $datosLogin = [
      "USERNAME" => $username,
      "PASSWORD" => $password
    ];

    $datosCuenta = LoginModel::loginModel($datosLogin);

    if ($datosCuenta->rowCount() == 1) {
      $row = $datosCuenta->fetch();

      session_start(['name' => 'STD']);
      $_SESSION['id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['privilegio'] = $row['privilegio'];

      return header("Location: " . SERVERURL . "persona-list/");
    } else {
      echo
      '
      <script>
      Swal.fire({
        title: "Ocurrió un error inesperado",
        text: "El NOMBRE DE USUARIO o la CONTRASEÑA son incorrectos",
        icon: "error",
        confirmButtonText: "Aceptar"
      });
      ';
      exit();
    }
  }
}
