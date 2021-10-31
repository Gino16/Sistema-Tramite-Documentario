<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img class="img-thumbnail img-fluid" style="width: 100px;" src="<?= SERVERURL ?>views/img/logo_rojo.png" alt="Logo UNS">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="#">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= SERVERURL ?>persona-list/">Personas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Solicitudes</a>
        </li>
      </ul>
      <div class="d-flex">
        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-alt"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg-end dropdown-menu-sm-start" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>

            <hr class="dropdown-divider">

            <form action="">
              <button class="dropdown-item bg-danger text-white" type="submit">Cerrar Sesión</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>