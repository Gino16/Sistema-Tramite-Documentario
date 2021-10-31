<?php
$peticionAjax = true;

require_once '../config/APP.php';

if (isset($_POST['dni_ruc_save']) || isset($_POST['persona_id_del']) || isset($_POST['persona_id_up'])) {

  require_once '../controllers/personaController.php';
  $personaController = new PersonaController();

  // Guardar persona
  if (isset($_POST['dni_ruc_save'])) {
    $personaController->savePersonaController();
  }

  // Eliminar persona
  if (isset($_POST['persona_id_del'])) {
    $personaController->deletePersonaController();
  }

  // Actualizar persona
  if (isset($_POST['persona_id_up'])) {
    $personaController->updatePersonaController();
  }
}
