<div class="container mt-4">
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">Listado de Usuarios</h2>
    </div>
    <div class="card-body">
      <div class="input-group my-3">
        <a class="btn btn-success" href="<?= SERVERURL ?>usuario-new">Registrar Nuevo Usuario</a>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre Usuario</th>
              <th>Contraseña</th>
              <th>Apellidos y Nombre</th>
              <th>Rol</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once './controllers/usuarioController.php';
            $usuarioController = new UsuarioController();
            echo $usuarioController->paginadorUsuarioController($pagina[1], 5, '', $pagina[0], '');
            ?>
      </div>
    </div>
  </div>
</div>