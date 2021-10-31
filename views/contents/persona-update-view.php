<div class="container align-items-center">
  <div class="row justify-content-center">
    <?php
    require_once './controllers/personaController.php';
    $personaController = new PersonaController();

    $datosPersona = $personaController->datosPersonaController('Unico', $pagina[1]);
    if ($datosPersona->rowCount() == 1) {
      $campos = $datosPersona->fetch();
    ?>

      <div class="col-6">
        <a class="btn btn-outline-secondary mb-4" href="<?= SERVERURL ?>persona-list/">Regresar</a>
        <div class="text-center mb-3">
          <h2 class="h2">Actualizar Datos de Persona</h2>

        </div>
        <form action="<?= SERVERURL ?>ajax/personaAjax.php" class="FormularioAjax" method="post" data-form="update" autocomplete="off">
          <input type="hidden" name="persona_id_up" value="<?= $pagina[1] ?>">
          <div class="mb-3">
            <label class="form-label" for="dni_ruc">DNI/RUC:</label>
            <input class="form-control" type="text" name="dni_ruc_up" id="dni_ruc" value="<?= $campos['dni_ruc'] ?>">
          </div>
          <div class="mb-3">
            <label class="form-label" for="nombre">Nombres:</label>
            <input class="form-control" type="text" name="nombre_up" id="nombre" value="<?= $campos['nombre'] ?>">
          </div>
          <div class="mb-3">
            <label class="form-label" for="apellido">Apellido:</label>
            <input class="form-control" type="text" name="apellido_up" id="apellido" value="<?= $campos['apellido'] ?>">
          </div>
          <div class=" mb-3">
            <label class="form-label" for="correo">Correo:</label>
            <input class="form-control" type="email" name="correo_up" id="correo" value="<?= $campos['correo'] ?>">
          </div>
          <div class="mb-3">
            <label class="form-label" for="cod_estudiante">Codigo de Estudiante:</label>
            <input class="form-control" type="text" name="cod_estudiante_up" id="cod_estudiante" value="<?= (isset($campos['cod_estudiante'])) ? $campos['cod_estudiante'] : '' ?>">
          </div>
          <div class="mb-3">
            <label for="puesto">Puesto:</label>
            <select class="form-select" name="puesto_id_up">
              <?php
              require_once './controllers/personaController.php';
              $personaController = new PersonaController();
              echo $personaController->listarPuestos();
              ?>
            </select>
          </div>
          <input class="btn btn-primary" type="submit" value="Actualizar Datos">
        </form>
      </div>
    <?php
    } else { ?>
      <div class="alert alert-danger text-center" role="alert">
        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
        <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
        <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
      </div>
    <?php
    }
    ?>
  </div>
</div>