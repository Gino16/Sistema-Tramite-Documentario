<div class="container align-items-center">
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="align-content-center">
        <h2 class="h2 align-align-self-center">Registro de Usuario</h2>

      </div>
      <form action="<?= SERVERURL ?>ajax/usuarioAjax.php" class="FormularioAjax" method="post" data-form="save" autocomplete="off">
        <div class="mb-3">
          <label class="form-label" for="username">Nombre de usuario</label>
          <input class="form-control" type="text" name="username" id="username">
        </div>
        <div class="mb-3">
          <label class="form-label" for="password">Contraseña</label>
          <input class="form-control" type="password" name="password" id="password">
        </div>
        <div class="mb-3">
          <label class="form-label" for="password2">Repita la Contraseña</label>
          <input class="form-control" type="password" name="password2" id="password2">
        </div>
        <div class="mb-3">
          <input class="form-check-input" type="checkbox" name="enabled" id="enabled">
          <label class="form-check-label" for="enabled">Activar usuario</label>
        </div>

        <div class="mb-3">
          <label for="persona">Persona a enlazar</label>
          <select class="form-select" name="dni_ruc">
            <?php
            require_once './controllers/personaController.php';
            $personaController = new PersonaController();
            $personas = $personaController->listarPersonas();
            foreach ($personas as $persona) : ?>
              <option value="<?= $persona['dni_ruc']; ?>"><?= $persona['apellido'] . ', ' . $persona['nombre']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <input class="btn btn-primary" type="submit" value="Registrar">
        </div>
      </form>
    </div>
  </div>
</div>