<div class="container mt-4">
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">Listado de Usuarios</h2>
    </div>
    <div class="card-body">
      <div class="input-group my-3">
        <a class="btn btn-success" href="<?= SERVERURL ?>./persona-new">Registrar Nueva Persona</a>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre Usuario</th>
              <th>Contraseña</th>
              <th>Rol</th>
              <th>Apellidos y Nombre</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once './controllers/usuarioController.php';
            $usuarioController = new UsuarioController();
            $usuarios = $usuarioController->listarUsuarios();
            foreach ($usuarios as $usuario) :
            ?>
              <tr>
                <th><?= $usuario['usuario_id'] ?></th>
                <th><?= $usuario['username'] ?></th>
                <th><?= $usuario['password'] ?></th>
                <th><?= $usuario[''] ?></th>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>