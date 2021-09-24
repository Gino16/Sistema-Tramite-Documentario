<div class="container align-items-center">
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="text-center mb-3">
        <h2 class="h2">Registro de Personas</h2>

      </div>
      <form action="" method="post">
        <div class="mb-3">
          <label class="form-label" for="dni_ruc">DNI/RUC:</label>
          <input class="form-control" type="text" name="dni_ruc" id="dni_ruc">
        </div>
        <div class="mb-3">
          <label class="form-label" for="nombre">Nombres:</label>
          <input class="form-control" type="text" name="nombre" id="nombre">
        </div>
        <div class="mb-3">
          <label class="form-label" for="apellido">Apellido:</label>
          <input class="form-control" type="text" name="apellido" id="apellido">
        </div>
        <div class="mb-3">
          <label class="form-label" for="correo">Correo:</label>
          <input class="form-control" type="email" name="correo" id="correo">
        </div>
        <div class="mb-3">
          <label class="form-label" for="cod_estudiante">Codigo de Estudiante:</label>
          <input class="form-control" type="text" name="cod_estudiante" id="cod_estudiante">
        </div>
        <div class="mb-3">
          <label for="puesto">Puesto:</label>
          <select class="form-select" name="id_puesto">
            <?php
            require_once './controllers/personaController.php';
            $personaController = new PersonaController();
            echo $personaController->listPuestos();
            ?>
          </select>
        </div>
        <input class="btn btn-primary" type="submit" value="Registrar persona">
      </form>
    </div>
  </div>
</div>

<?php
if (isset($_POST['dni_ruc']) && isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['id_puesto'])) {
  $personaController->savePersona();
}


?>