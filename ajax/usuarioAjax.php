<?php
$peticionAjax = true;

require_once '../config/APP.php';

if (isset($_POST['username'])) {
  require_once '../controllers/usuarioController.php';
  $usuarioController = new UsuarioController();

  //Registrar usuario
  if (isset($_POST['username'])) {
    $usuarioController->saveUsuarioController();
  }
}
