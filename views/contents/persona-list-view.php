<div class="container mt-4">
  <div class="card">
    <div class="card-header text-center">
      <h2 class="card-title">Listado de Personas</h2>
    </div>
    <div class="card-body">
      <div class="input-group my-3">
        <a class="btn btn-success" href="<?=SERVERURL?>./persona-new">Registrar Nueva Persona</a>
      </div>
      <table class="table table-striped table-hover ">
        <thead>
          <tr>
            <th>#</th>
            <th>DNI/RUC</th>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Cod. Estudiante</th>
            <th>Puesto</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          require_once './controllers/personaController.php';
          $personaController = new PersonaController();
          echo $personaController->listPersonas();
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php

?>