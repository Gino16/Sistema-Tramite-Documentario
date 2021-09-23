<div class="container">
  <div class="text-center">
    <h2>Listado de Personas</h2>
  </div>
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>DNI/RUC</th>
        <th>Apellido</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Cod. Estudiante</th>
        <th>Puesto</th>
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

<?php

?>