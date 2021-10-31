<div class="container align-items-center">
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="text-center mb-3">
        <h2 class="h2">Actualizar Datos de Persona</h2>

      </div>
      <form action="" method="post">
        <div class="mb-3">
          <label class="form-label" for="dni_ruc">DNI/RUC:</label>
          <input class="form-control" type="text" name="dni_ruc" id="dni_ruc" required>
        </div>
        <div class="mb-3">
          <label class="form-label" for="nombre">Nombres:</label>
          <input class="form-control" type="text" name="nombre" id="nombre" required>
        </div>
        <div class="mb-3">
          <label class="form-label" for="apellido">Apellido:</label>
          <input class="form-control" type="text" name="apellido" id="apellido" required>
        </div>
        <div class="mb-3">
          <label class="form-label" for="correo">Correo:</label>
          <input class="form-control" type="email" name="correo" id="correo" required>
        </div>
        <div class="mb-3">
          <label class="form-label" for="cod_estudiante">Codigo de Estudiante:</label>
          <input class="form-control" type="text" name="cod_estudiante" id="cod_estudiante" required>
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