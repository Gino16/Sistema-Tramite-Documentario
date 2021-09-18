<?php
if (!isset($_SESSION['ingresar'])) {
?>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-6 col-md-3 ">
        <div class="text-end">
          <h2 class="fw-bold text-center pt-5 mb-5">Bienvenido</h2>
        </div>
        <!-- LOGIN PAGE -->
        <form action="#">
          <div class="mb-4">
            <label class="form-label" for="username">Correo Electronico:</label>
            <input class="form-control" type="email" name="username" id="username">
          </div>
          <div class="mb-4">
            <label class="form-label" for="password">Contraseña:</label>
            <input class="form-control" type="password" name="password" id="password">
          </div>
          <div class="mb-4 form-check">
            <input class="form-check-input" type="checkbox" name="connected" id="connected">
            <label class="form-check-label" for="connected">Mantenerme conectado</label>
          </div>

          <div class="d-grid">
            <button class="btn btn-primary" type="submit">Iniciar Sesión</button>
          </div>

          <div class="my-3">
            <span><a href="#">Recuperar Contraseña</a></span>
          </div>
        </form>

      </div>
    </div>
  </div>
<?php } else {
  header('Location:' . SERVERURL . 'inicio');
} ?>