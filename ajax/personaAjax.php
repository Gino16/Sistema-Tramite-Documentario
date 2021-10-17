<?php
$peticionAjax = true;

require_once '../config/APP.php';

if (isset($_POST['dni_ruc_save'])) {

  require_once '../controllers/personaController.php';
  $personaController = new PersonaController();

  if (isset($_POST['dni_ruc_save'])) {
    $personaController->savePersonaController();
  }
}
