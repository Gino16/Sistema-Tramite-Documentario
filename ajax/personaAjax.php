<?php
$peticionAjax = true;

require_once '../config/APP.php';

if (isset($_POST['dni_ruc_save']) || isset($_POST['persona_id_del'])) {

  require_once '../controllers/personaController.php';
  $personaController = new PersonaController();

  if (isset($_POST['dni_ruc_save'])) {
    $personaController->savePersonaController();
  }

  if (isset($_POST['persona_id_del'])) {
    $personaController->deleteClienteController();
  }
}
