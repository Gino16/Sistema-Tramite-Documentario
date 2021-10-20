<div class="container-fluid align-items-center">
  <div class="row justify-content-center">
    <div class="col-6">
      <a class="btn btn-outline-secondary" href="<?= SERVERURL ?>persona-list/">Regresar</a>
      <div class="text-center mb-3">
        <h2 class="h2">Registro de Personas</h2>
      </div>
      <form class="FormularioAjax" action="<?= SERVERURL ?>ajax/personaAjax.php" method="post" data-form="save">
        <div class="mb-3">
          <label class="form-label" for="dni_ruc_save">DNI/RUC:</label>
          <input class="form-control" type="text" name="dni_ruc_save" id="dni_ruc_save" pattern="[0-9-]{1,27}" title="Solo numeros y tamaño 1 a 27 dígitos">
        </div>
        <div class="mb-3">
          <label class="form-label" for="nombre">Nombres:</label>
          <input class="form-control" type="text" name="nombre" id="nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}">
        </div>
        <div class=" mb-3">
          <label class="form-label" for="apellido">Apellido:</label>
          <input class="form-control" type="text" name="apellido" id="apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}">
        </div>
        <div class="mb-3">
          <label class="form-label" for="correo">Correo:</label>
          <input class="form-control" type="email" name="correo" id="correo">
        </div>
        <div class="mb-3">
          <label class="form-label" for="cod_estudiante">Codigo de Estudiante:</label>
          <input class="form-control" type="text" name="cod_estudiante" id="cod_estudiante" pattern="[0-9]{10}" title="Solo numero de 10 dígitos o dejar vacío">
        </div>
        <div class="mb-3">
          <label for="puesto">Puesto:</label>
          <select class="form-select" name="id_puesto">
            <?php
            require_once './controllers/personaController.php';
            $personaController = new PersonaController();
            echo $personaController->listarPuestos();
            ?>
          </select>
        </div>
        <input class="btn btn-primary" type="submit" value="Registrar persona">
      </form>
    </div>
  </div>
</div>